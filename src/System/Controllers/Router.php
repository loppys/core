<?php

namespace Vengine\System\Controllers;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Vengine\AbstractModule;
use Vengine\Cache\Drivers\RoutesCacheDriver;
use Vengine\System\Settings\Storages\AccessLevelStorage;
use function FastRoute\simpleDispatcher;
use Vengine\System\Exceptions\AccessDeniedException;
use Vengine\System\Exceptions\MethodNotAllowedException;
use Vengine\System\Exceptions\PageNotFoundException;
use Vengine\System\Settings\Permissions;
use Vengine\System\Settings\Storages\MethodType;
use Vengine\System\Traits\ContainerTrait;
use Vengine\Injection;
use Vengine\App;

class Router implements Injection
{
    use ContainerTrait;

    protected const API_PREFIX = '/api/v1';

    protected Request $request;

    protected string $requestUri;

    protected string $path;

    protected string $scheme;

    protected string $host;

    protected string $method;

    protected Permissions $permissions;

    protected array $routes = [];

    private static Dispatcher $dispatcher;

    private RoutesCacheDriver $cacheDriver;

    public function __construct(Permissions $permissions)
    {
        $this->request = App::getRequest();

        $this->container = $this->getContainer();

        $this->permissions = $permissions;

        $this->requestUri = $this->request->getRequestUri();
        $this->path = $this->request->getPathInfo();
        $this->scheme = $this->request->getScheme();
        $this->host = $this->request->getHttpHost();
        $this->method = $this->request->getMethod();

        $this->cacheDriver = $this->cache->routes;
    }

    public function addRouteList(array $pathList): Router
    {
        $this->routes = array_merge($this->routes, $pathList);

        return $this;
    }

    public function addRoute(array $route): Router
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * @param string|null $route
     *
     * @throws AccessDeniedException
     * @throws MethodNotAllowedException
     * @throws PageNotFoundException
     */
    public function handle(string $route = null): void
    {
        if (empty(static::$dispatcher)) {
            if ($this->hasCachedRoutes()) {
                static::$dispatcher = $this->getCachedRoutes();
            } else {
                $this->collectRoutes();
            }
        }

        $this->route($route);
    }

    protected function collectRoutes(): void
    {
        $structure = $this->structure;

        $routes = require $structure->coreConfig . 'routes.php';
        $routesApi = require $structure->coreConfig . 'routesApi.php';

        $userRoutes = $structure->userConfig . 'routes.php';
        $userRoutesApi = $structure->userConfig . 'routesApi.php';

        if (!empty($this->routes)) {
            $routes = array_merge($routes, $this->routes);
        }

        if (file_exists($userRoutes)) {
            $userRoutes = require $userRoutes;

            $routes = array_merge($routes, (array)$userRoutes);
        }

        if (file_exists($userRoutesApi)) {
            $userRoutesApi = require $userRoutesApi;

            $routesApi = array_merge($routesApi, (array)$userRoutesApi);
            foreach ($routesApi as $apiRoute) {
                $apiRoute['route'] = self::API_PREFIX . $apiRoute['route'];

                $routes[] = $apiRoute;
            }
        }

        $routeGeneration = static function (RouteCollector $routeCollector, array $routeInfo) {
            switch ($routeInfo['method']) {
                case MethodType::GET:
                    $routeCollector->get($routeInfo['route'], $routeInfo['handler']);
                    break;
                case MethodType::POST:
                    $routeCollector->post($routeInfo['route'], $routeInfo['handler']);
                    break;
                case MethodType::PATCH:
                    $routeCollector->patch($routeInfo['route'], $routeInfo['handler']);
                    break;
                case MethodType::PUT:
                    $routeCollector->put($routeInfo['route'], $routeInfo['handler']);
                    break;
                case MethodType::DELETE:
                    $routeCollector->delete($routeInfo['route'], $routeInfo['handler']);
                    break;
                default:
                    $routeCollector->get($routeInfo['route'], $routeInfo['handler']);
                    $routeCollector->post($routeInfo['route'], $routeInfo['handler']);
                    $routeCollector->patch($routeInfo['route'], $routeInfo['handler']);
                    $routeCollector->put($routeInfo['route'], $routeInfo['handler']);
                    $routeCollector->delete($routeInfo['route'], $routeInfo['handler']);
                    break;
            }
        };

        static::$dispatcher = simpleDispatcher(
            static function (RouteCollector $routeCollector) use ($routes, $routesApi, $routeGeneration) {
                $tmpRoutes = [];

                foreach ($routes as $route) {
                    if (!empty($route['method'])) {
                        $tmpRoutes[$route['method'] . $route['route']] = $route;
                    } else {
                        $tmpRoutes[$route['route']] = $route;
                    }
                }

                foreach ($tmpRoutes as $routeInfo) {
                    $routeGeneration($routeCollector, $routeInfo);
                }

                unset($tmpRoutes);
            }
        );

        $this->setRoutesCache(static::$dispatcher);
    }

    /**
     * @param string|null $route
     *
     * @throws AccessDeniedException
     * @throws MethodNotAllowedException
     * @throws PageNotFoundException
     */
    protected function route(string $route = null): void
    {
        $routeInfo = static::$dispatcher->dispatch($this->method, $route ?: $this->path);

        if (!empty($routeInfo[0])) {
            $method = array_shift($routeInfo);
        } else {
            $method = Dispatcher::NOT_FOUND;
        }

        switch ($method) {
            case Dispatcher::NOT_FOUND:
                static::pageNotFound();
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
            case Dispatcher::FOUND:
                [$handler, $argumentList] = $routeInfo;

                if (is_array($handler)) {
                    $controller = $handler['controller'];
                    $method = $handler['method'];
                    $access = (int)$handler['access'];

                    $typeView = $access !== AccessLevelStorage::API ? 'default' : 'api';

                    if ($typeView === 'api') {
                        $token = $this->request->get('token')
                            ?? (json_decode($this->request->getContent())->token ?? null)
                            ?? $this->user->getToken()
                            ?: null
                        ;
                        
                        $server = $_SERVER['SERVER_ADDR'];

                        if ($this->request->getClientIp() === $server) {
                            $token = $this->config->token;
                        }

                        if (empty($token)) {
                            throw new AccessDeniedException('token not found', 401);
                        }

                        if ($token !== $this->config->token) {
                            throw new AccessDeniedException('invalid token', 401);
                        }
                    } elseif (!$this->permissions->checkAccess($access)) {
                        throw new AccessDeniedException('Нет прав для просмотра страницы.');
                    }

                    $controller = $this->container->getBuilder()->createObject($controller);

                    if ($controller instanceof AbstractModule) {
                        $controller->process()->render();

                        break;
                    }

                    $this->container->getBuilder()->invoke(
                        $controller,
                        $method,
                        $argumentList
                    );
                } else {
                    $this->container->getBuilder()->createObject($handler, $argumentList);
                }
                break;
        }
    }

    /**
     * @throws PageNotFoundException
     */
    public static function pageNotFound(): void
    {
        throw new PageNotFoundException();
    }

    public static function redirect(string $path = '/'): void
    {
        header("Location: {$path}");
    }

    protected function hasCachedRoutes(string $key = 'sys.routes'): bool
    {
        return $this->cacheDriver->has($key);
    }

    protected function getCachedRoutes(string $key = 'sys.routes'): ?Dispatcher
    {
        return $this->cacheDriver->get($key);
    }

    protected function setRoutesCache(?Dispatcher $routes, string $key = 'sys.routes'): static
    {
        $this->cacheDriver->set($key, $routes);

        return $this;
    }
}

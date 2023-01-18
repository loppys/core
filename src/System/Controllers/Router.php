<?php

namespace Vengine\System\Controllers;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use function FastRoute\cachedDispatcher;
use function FastRoute\simpleDispatcher;
use Vengine\System\Exceptions\AccessDeniedException;
use Vengine\System\Exceptions\MethodNotAllowedException;
use Vengine\System\Exceptions\PageNotFoundException;
use Vengine\System\Settings\Controllers\AccessController;
use Vengine\System\Settings\Permissions;
use Vengine\System\Settings\Storages\MethodType;
use Vengine\System\Traits\ContainerTrait;
use Vengine\Injection;
use Vengine\App;

class Router implements Injection
{
    use ContainerTrait;

    protected const API_PREFIX = '/api/v1';

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $requestUri;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var Permissions
     */
    protected $permissions;

    /**
     * @var Dispatcher
     */
    private static $dispatcher;

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
            $this->collectBaseRoutes();
        }

        $this->route($route);
    }

    protected function collectBaseRoutes(): void
    {
        $dir = dirname(__DIR__, 2);

        $routes = require $dir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';
        $routesApi = require $dir . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routesApi.php';

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
            }
        };

        static::$dispatcher = simpleDispatcher(
            static function (RouteCollector $routeCollector) use ($routes, $routesApi, $routeGeneration) {
                foreach ($routes as $route) {
                    $routeGeneration($routeCollector, $route);
                }

                foreach ($routesApi as $route) {
                    $route['route'] = self::API_PREFIX . $route['route'];

                    $routeGeneration($routeCollector, $route);
                }
            }
        );
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
        $routeInfo = static::$dispatcher->dispatch($this->method, $route ?: $this->requestUri);

        if (!empty($routeInfo[0])) {
            $method = array_shift($routeInfo);
        } else {
            $method = Dispatcher::NOT_FOUND;
        }

        switch ($method) {
            case Dispatcher::NOT_FOUND:
                static::pageNotFound();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException();
                break;
            case Dispatcher::FOUND:
                [$handler, $argumentList] = $routeInfo;

                if (is_array($handler)) {
                    $controller = $handler['controller'];
                    $method = $handler['method'];
                    $access = $handler['access'];

                    if (!$this->permissions->checkAccess($access)) {
                        throw new AccessDeniedException();
                    }

                    $this->container->getBuilder()->invoke(
                        $this->container->getBuilder()->createObject($controller),
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
}

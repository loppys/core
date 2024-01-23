<?php

namespace Vengine;

use Loader\System\Interfaces\PackageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Vengine\Packages\Updater\Components\Configurator;
use Vengine\Packages\Settings\Storage\ConstStorage;
use Vengine\Packages\Updater\Controllers\UpdaterPageController;
use Vengine\Packages\User\Factory\UserFactory;
use Vengine\System\Actions;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Components\Page\Render;
use Vengine\System\Controllers\Router;
use Vengine\System\Exceptions\AppException;
use Vengine\System\Settings\Storages\AccessLevelStorage;
use Vengine\System\Settings\Storages\MethodType;
use Vengine\System\Settings\Storages\PermissionType;
use Vengine\System\Settings\Structure;
use Vengine\System\Traits\ContainerTrait;
use Whoops\Handler\PrettyPageHandler;
use Loader\System\Container;
use Whoops\Run;
use Exception;

final class App implements Injection
{
    use ContainerTrait;

    protected static App $instance;

    private bool $debugMode;

    public function __construct(bool $debug = false)
    {
        $this->debugMode = $debug;

        $this->logWriter();

        $session = self::getSession();

        $session->start();

        self::getRequest()->setSession($session);

        if (empty(self::$instance)) {
            $this->init();
        }
    }

    public function init(): void
    {
        $this->container = new Container();

        $this->container->setShared(
            'structure',
            $this->container->createObject(Structure::class)
        );

        $corePackage = $this->structure->coreConfig . ConstStorage::APP_CONFIG_NAME;
        $userPackage = $this->structure->userConfig . ConstStorage::APP_CONFIG_NAME;

        if (file_exists($corePackage)) {
            $corePackage = require($corePackage);


            if (is_array($corePackage)) {
                $this->container->packageCollect(
                    $corePackage
                );
            }
        }

        if (file_exists($userPackage)) {
            $userPackage = require($userPackage);

            if (is_array($corePackage)) {
                $this->container->packageCollect(
                    $userPackage
                );
            }
        }

        $this->container->setShared(
            'configurator',
            $this->container->createObject(Configurator::class)
        );

        UserFactory::create();

        $this->container->setShared(
            'render',
            $this->container->createObject(Render::class)
        );

        $this->render->setTemplateFolder('/www/template/');

        $this->container->setShared(
            'router',
            $this->container->createObject(Router::class)
        );

        if (!file_exists(Configurator::getConfigPath())) {
            $this->router->addRouteList(
                [
                    [
                        'route' => '/install/',
                        'method' => MethodType::GET,
                        'handler' => [
                            'controller' => UpdaterPageController::class,
                            'method' => 'indexAction',
                            'access' => AccessLevelStorage::ALL
                        ]
                    ],
                    [
                        'route' => '/install/',
                        'method' => MethodType::POST,
                        'handler' => [
                            'controller' => UpdaterPageController::class,
                            'method' => 'indexAction',
                            'access' => AccessLevelStorage::ALL
                        ]
                    ],
                ]
            );

            $this->router->handle('/install/');

            die();
        }

        $this->container->setShared(
            'adapter',
            $this->container->createObject(Adapter::class)
        );

        $this->adapter->connect();

        self::$instance = $this;
    }

    public function run(): void
    {
        $this->container->setShared(
            'startup',
            $this->container->createObject(Startup::class)
        );

        $request = self::getRequest();

        $subj = $request->get('subj');
        $fn = $request->get('fn');

        if ($subj && $fn) {
            $this->container->getBuilder()->invoke(
                $this->container->createObject(Actions::class),
                'handle',
                [
                    $subj,
                    $fn
                ]
            );
        }

        try {
            $this->startup->run();
        } catch (Exception $e) {
            http_response_code($e->getCode());

            print $e->getMessage();
        }
    }

    /**
     * @throws AppException
     */
    public static function app(): self
    {
        if (empty(self::$instance)) {
            throw new AppException('App not init');
        }

        return self::$instance;
    }

    /**
     * @deprecated
     * @see Container::createObject()
     */
    public function createObject(string $class, array $arguments = []): object
    {
        return $this->container->createObject($class, $arguments);
    }

    /**
     * @deprecated
     * @see Container::getPackage()
     */
    public function getPackage(string $name): PackageInterface
    {
        return $this->container->getPackage($name);
    }

    public static function getRequest(): Request
    {
        static $request;

        if (!empty($request)) {
            return $request;
        }

        return $request = Request::createFromGlobals();
    }

    public static function getSession(): Session
    {
        static $session;

        if (!empty($session)) {
            return $session;
        }

        return $session = new Session();
    }

    public function logWriter(): void
    {
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/logs/errors.log');

        $developer = false;

        $user = UserFactory::getUser();
        if ($user !== null) {
            $developer = $user->getRole() === PermissionType::DEVELOPER;
        }

        if (class_exists(Run::class) && ($this->debugMode || $developer)) {
            $whoops = new Run();
            $whoops->pushHandler(new PrettyPageHandler);
            $whoops->register();
        }
    }
}

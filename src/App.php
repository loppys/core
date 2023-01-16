<?php

namespace Vengine;

use Loader\System\Interfaces\PackageInterface;
use Symfony\Component\HttpFoundation\Request;
use Vengine\Packages\Settings\Storage\ConstStorage;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Components\Page\Render;
use Vengine\System\Settings\Structure;
use Vengine\System\Traits\ContainerTrait;
use Vengine\Packages\Updater\Components\RenderUpdater;
use Loader\System\Container;
use Exception;

class App implements Injection
{
    use ContainerTrait;

    /**
     * @var App
     */
    protected static $instance;

    public function __construct()
    {
        $this->logWriter();

        if (empty(static::$instance)) {
            $this->init();
        }
    }

    public function init(): void
    {
        $this->container = new Container();

        $this->container->setShared('container', $this->container);

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . ConstStorage::DEFAULT_CONFIG_NAME)) {
            $this->container->getBuilder()->invoke(
                $this->createObject(RenderUpdater::class),
                'process'
            );

            die();
        }

        $this->container->setShared(
            'structure',
            $this->createObject(Structure::class)
        );

        $this->container->setShared(
            'adapter',
            $this->createObject(Adapter::class)
        );

        $this->adapter->connect();

        $this->container->setShared(
            'base',
            $this->createObject(Base::class)
        );

        $this->container->setShared(
            'render',
            $this->createObject(Render::class)
        );

        $this->container->setShared(
            'startup',
            $this->createObject(Startup::class)
        );

        $corePackage = $this->structure->coreConfig . ConstStorage::APP_CONFIG_NAME;
        $userPackage = $this->structure->config . ConstStorage::APP_CONFIG_NAME;

        if (file_exists($corePackage)) {
            $this->container->packageCollect(
                require($corePackage)
            );
        }

        if (file_exists($userPackage)) {
            $this->container->packageCollect(
                require($userPackage)
            );
        }

        static::$instance = $this;
    }

    public function run(): void
    {
        try {
            $this->startup->run();
        } catch (Exception $e) {
            http_response_code($e->getCode());

            print $e->getMessage();
        }
    }

    public static function app(): self
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function createObject(string $class, array $arguments = []): ?object
    {
        return $this->container->getBuilder()->createObject($class, $arguments);
    }

    public function getPackage(string $name): PackageInterface
    {
        return $this->container->getPackage($name);
    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    public function logWriter(): void
    {
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/logs/errors.log');

        $whoops = new \Whoops\Run();
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $whoops->register();
    }
}
<?php


namespace Vengine;

use Symfony\Component\HttpFoundation\Request;
use Exception;
use Loader;

class App
{
    /**
     * @var App
     */
    protected static $instance;

    public function __construct()
    {
    }

    public function run(): void
    {
        try {
            Loader::callModule('Startup');
        } catch (Exception $e) {
            http_response_code($e->getCode());
        }
    }

    public static function app(): self
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function get(string $name): ?object
    {
        return Loader::getComponent($name);
    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }
}
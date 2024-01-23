<?php

namespace Vengine\System\Components\Page;

use Vengine\App;
use Vengine\System\Actions;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Controllers\Router;
use Vengine\System\DefaultController;

abstract class AbstractPageController extends DefaultController
{
    protected Adapter $adapter;

    protected Render $render;

    protected string $title;

    public static string $route;

    public function __construct()
    {
        parent::__construct();

        $this->adapter = $this->app->adapter;
        $this->render = $this->app->render;

        $this->render->setTitle($this->title ?: 'Default Title');

        $this->prepareData();
    }

    public function indexAction(): void
    {
        $this->render();
    }

    public function render(): void
    {
        $this->render->render();
    }

    abstract public function prepareData(): void;
}

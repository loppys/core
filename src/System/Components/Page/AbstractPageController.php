<?php

namespace Vengine\System\Components\Page;

use Vengine\System\Actions;
use Vengine\System\Components\Database\Adapter;
use Vengine\System\Controllers\Router;
use Vengine\System\DefaultController;

abstract class AbstractPageController extends DefaultController
{
    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * @var Render
     */
    protected $render;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    public static $route;

    public function __construct(
        Adapter $adapter,
        Render $render,
        Router $router,
        Actions $actions
    ) {
        parent::__construct($router, $actions);

        $this->adapter = $adapter;
        $this->render = $render;

        $this->render->setTitle($this->title ?: 'Default Title');

        $this->prepareData();
    }

    public function indexAction(): void
    {
        $this->render->render();
    }

    abstract public function prepareData(): void;
}

<?php

namespace Vengine\System\Components\Page;

use Vengine\System\Database\SystemAdapter;
use Vengine\System\DefaultController;

/**
 * @TODO перенести в Controllers
 */
abstract class AbstractPageController extends DefaultController
{
    protected SystemAdapter $adapter;

    protected Render $render;

    protected string $title;

    public static string $route;

    public function __construct()
    {
        parent::__construct();

        $this->adapter = $this->app->adapter;
        $this->render = $this->app->render;

        $this->render->setTitle($this->title ?? 'Default Title');
    }

    public function indexAction(): void
    {
        $this->render();
    }

    public function render(): void
    {
        $this->render->render();
    }

    /**
     * @deprecated
     * @see use other methods
     */
    public function prepareData(): void
    {

    }
}

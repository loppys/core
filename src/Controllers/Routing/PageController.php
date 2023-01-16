<?php

namespace Vengine\Controllers\Routing;

use Vengine\Base;
use Vengine\Render\RenderPage;
use Vengine\Controllers\Routing\AbstractPageController;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @deprecated
 */
class PageController extends AbstractPageController
{
    public $controller;
    public $render;
    public $parameters;

    public function route()
    {
        if (!empty($this->interface->localPages)) {
            $name = substr($this->interface->uri['path'], 1);
            $localPage = $this->interface->localPages->getList()[$name];
            unset($name);
        }

        $arrayObject = new \ArrayObject($this->page);
        $arrayObject->setFlags(\ArrayObject::ARRAY_AS_PROPS);
        $this->page = $arrayObject;

        $url = $this->page->url;

        if ($localPage) {
            if (in_array($this->interface->page, $localPage) && empty($url)) {
                $arrayObject = new \ArrayObject($localPage);
                $arrayObject->setFlags(\ArrayObject::ARRAY_AS_PROPS);
                $this->page = $arrayObject;
            }
        }

        if ($this->page->render == 'standart') {
            $this->render = RenderPage::class;
        } else {
            $this->render = $this->page->render;
        }

        if (!$this->page->visible) {
            $this->missingPage();
        }

        $param = [];

        if ($this->page->param) {
            $url = $this->page->url;
            $param = explode(', ', $this->page->param);

            foreach ($param as $key => $value) {
                $url .= '/{' . $value . '}';
            }

            if ($this->page->absolute !== $url) {
                $this->page->absolute = $url;
            }
        } else {
            $this->page->absolute = $this->page->url;
        }

        if ($this->page->controller === 'default') {
            $this->controller = RenderPage::class;
        } else {
            $this->controller = $this->page->controller;
        }

        switch (true) {
            case $this->page->type === 'page':
                $route = new Route(
                    $this->page->absolute,
                    [
                        'controller' => $this->controller
                    ],
                    $param
                );

                if ($route) {
                    $routes = new RouteCollection();
                    $context = new RequestContext();

                    if ($this->interface->uri['path'] === '/') {
                        $defaultPage = $this->getStandartPage();

                        if (empty($defaultPage)) {
                            $this->missingPage();
                        }

                        return header('Location: /' . substr($defaultPage->url, 1), true, 301);
                    }

                    $context->fromRequest($this->request);

                    $routes->add('page', $route);
                    $matcher = new UrlMatcher($routes, $context);

                    try {
                        $parameters = $matcher->match($this->page->absolute);
                    } catch (\Exception $e) {
                        $this->missingPage();
                    }

                    $this->parameters = $parameters;

                    if ($this->page->controller === 'default') {
                        $parameters['controller'] = $this->controller;
                    }

                    if (!empty($parameters['controller']) && class_exists($parameters['controller'])) {
                        return new $parameters['controller']($this);
                    } else {
                        print('Что-то пошло не так!');
                        die();
                    }
                }
                break;

            default:
                $this->missingPage();
                break;
        }
    }
}

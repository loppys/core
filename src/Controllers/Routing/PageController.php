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

class PageController extends AbstractPageController
{
  public $controller;
  public $render;
  public $parameters;

  function __construct(Base $base)
  {
    parent::__construct($base);
  }

  public function route()
  {
    $localPage = $this->interface->localPages->getList();

    $arrayObject = new \ArrayObject($this->page);
    $arrayObject->setFlags(\ArrayObject::ARRAY_AS_PROPS);
    $this->page = $arrayObject;

    $url = $this->page->url;

    if (array_key_exists($this->interface->page, $localPage) && empty($url)) {
      $arrayObject = new \ArrayObject($localPage[$this->interface->page]);
      $arrayObject->setFlags(\ArrayObject::ARRAY_AS_PROPS);
      $this->page = $arrayObject;
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
          ]
        );

        if ($route) {
          $routes = new RouteCollection();
          $context = new RequestContext();

          if ($this->interface->uri['path'] === '/') {
            $defaultPage = $this->getStandartPage();

            if (empty($defaultPage)) {
              $this->missingPage();
            }

            return header('Location: /' . $defaultPage->url, true, 301);
          }

          $context->fromRequest($this->request);

          $routes->add('page', $route);

          $urlMatch = substr($this->interface->uri['requestUri'], 1);

          $matcher = new UrlMatcher($routes, $context);

          try {
            $parameters = $matcher->match($this->interface->uri['requestUri']);
          } catch (\Exception $e) {
            $this->missingPage();
          }

          $this->parameters = $parameters;

          if ($this->page->controller === 'default' ) {
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
      case $this->page->type === 'api':
        $route = new Route(
          $this->page->absolute,
          [
            'controller' => $this->controller
          ]
        );

        if ($route) {
          $routes = new RouteCollection();
          $context = new RequestContext();

          $context->fromRequest($this->request);

          $routes->add('api', $route);

          $urlMatch = substr($this->interface->uri['requestUri'], 1);

          $matcher = new UrlMatcher($routes, $context);
          $parameters = $matcher->match($this->interface->uri['requestUri']);

          if (!empty($parameters['controller']) && class_exists($parameters['controller'])) {
            return new $parameters['controller']($this);
          } else {
            print('Контроллер страницы не найден');
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

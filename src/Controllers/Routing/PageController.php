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
  function __construct(Base $base)
  {
    parent::__construct($base);
  }

  public function route()
  {
    $url = $this->page->url;

    if (empty($this->page->param)) {
      $param = json_encode([]);
    } else {
      $param = $this->page->param;
    }

    switch (true) {
      case $this->page->type === 'page':
        $route = new Route(
          $url,
          [
            'controller' => RenderPage::class
          ],
          json_decode($param, 1)
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

          $matcher = new UrlMatcher($routes, $context);
          $parameters = $matcher->match($context->getPathInfo());

          if (!empty($parameters['controller'])) {
            return new RenderPage($this);
          } else {
            print('empty page controller');
            die();
          }
        }
        break;
      case $this->page->type === 'api':
        $route = new Route($url, ['_controller' => RenderPage::class]);
        break;

      default:
        $this->missingPage();
        break;
    }
  }
}

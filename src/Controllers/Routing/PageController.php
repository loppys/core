<?php

namespace Vengine\Controllers\Routing;

use Vengine\Process;
use Vengine\Render\RenderPage;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class PageController
{
  public $page;

  protected $interface;
  protected $adapter;
  protected $process;
  protected $request;

  function __construct(Process $object)
  {
    $this->interface = $object->interface;
    $this->adapter = $object->adapter;
    $this->request = $object->request;
    $this->process = $object;

    $this->page = $this->getPage($this->interface->page);

    $this->route();
  }

  public function missingPage()
  {
    header("HTTP/1.0 404 Not Found");

    $this->process->error404();
  }

  public function route()
  {
    if ($this->page->url) {
      $url = $this->page->url;
    } else {
      $url = $this->page->page;
    }

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
            return header('Location: /' . $url, true, 301);
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

  public function getPage($page)
  {
    if (!$page) {
      return false;
    }

    $load = $this->adapter->findOne('pages', 'page = ? OR custom_url = ? OR url = ?', [$page, $page, $page]);

    if ($load->param_cls) {
      $load->param_cls = explode(", ", $load->param_cls);
    }

    if ($load->param_method) {
      $load->param_method = explode(", ", $load->param_method);
    }

    if ($load->tpl) {
      $load->tpl = explode(", ", $load->tpl);
    }

    if ($load->js) {
      $load->js = explode(", ", $load->js);
    }

  #Добавить видимость страниц в бд и добавить в проверку
    if (!empty($load)) {
      return $load;
    }else{
      return false;
    }
  }
}

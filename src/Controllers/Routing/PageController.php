<?php

namespace Vengine\Controllers\Routing;

use Vengine\Process;
use Vengine\Render\RenderPage;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class PageController
{
  public $page;

  protected $interface;
  protected $adapter;
  protected $process;

  function __construct(Process $object)
  {
    $this->interface = $object->interface;
    $this->adapter = $object->adapter;
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
    $url = $this->page->url;

    switch (true) {
      case $this->page->type === 'page':
        $route = new Route($url, ['_controller' => RenderPage::class]);

        if ($route) {
          $routes = new RouteCollection();
          $context = new RequestContext();

          $routes->add('page', $route);

          // d($routes);

          $matcher = new UrlMatcher($routes, $context);
          $parameters = $matcher->match($url);

          $generator = new UrlGenerator($routes, $context);
          $url = $generator->generate('page');
          d($generator);
          //доделать
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

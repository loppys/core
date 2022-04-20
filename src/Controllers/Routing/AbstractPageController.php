<?php

namespace Vengine\Controllers\Routing;

use Vengine\Base;

abstract class AbstractPageController
{
  public $page;

  protected $interface;
  protected $adapter;
  protected $base;
  protected $request;

  function __construct(Base $base)
  {
    $this->interface = $base->interface;
    $this->adapter = $base->adapter;
    $this->request = $base->request;
    $this->base = $base;

    $this->page = $this->getPage($this->interface->page);

    $this->route();
  }

  public function missingPage()
  {
    header("HTTP/1.0 404 Not Found");

    $this->base->error404();
  }

  public function getStandartPage()
  {
    return $this->adapter->findOne('pages', '`default` = ?', ['1']);
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

  abstract public function route();
}

<?php

namespace Vengine\Modules\CMS;

use Vengine\AbstractModule;

/**
 * @deprecated
 */
class Main extends AbstractModule
{
  public $module = 'CMS';
  public $version = '0.1.0';

  public $uri = [];

  private $adminPage = \Vengine\Modules\CMS\Pages\admin::class;

  public function render(): array
  {
    $this->setUri();

    array_shift($this->uri['detailPath']);

    if (empty($this->uri['detailPath'])) {
      $class = $this->adminPage;
    } else {
      $class = '\\Vengine\\Modules\\CMS\\Pages\\' . array_shift($this->uri['detailPath']);
    }

    if (class_exists($class)) {
      $page = new $class();
      $page->index();

      return $page->getRenderData();
    } else {
      $this->missingPage();
    }

    return [];
  }

  public function missingPage()
  {
    header("HTTP/1.0 404 Not Found");

    $this->error404();
  }

  private function setUri(): void
  {
    $request = $this->request;

    $this->uri = [
      'requestUri' => $request->getRequestUri(),
      'path' => $request->getPathInfo(),
      'scheme' => $request->getScheme(),
      'host' => $request->getHttpHost(),
      'method' => $request->getMethod(),
      'detailPath' => array_filter(explode('admin/', $request->getPathInfo()))
    ];
  }

  //Временно, пока не будут переделаны шаблоны
  public function tr($value)
  {
    return $value;
  }
}

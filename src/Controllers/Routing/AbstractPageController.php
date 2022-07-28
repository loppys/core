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

    $url = array_filter(explode('/', $this->interface->page));
    $url += ['urlPath' => $this->interface->page];

    $this->page = $this->getPage($url['urlPath']);

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

  public function setConfigPage(array $page)
  {

  }

  public function getConfigPages(): array
  {
    $config = [];

    if (file_exists($this->interface->structure['pages'])) {
      $config = require $this->interface->structure['pages'];
    }

    return $config;
  }

  public function getPage($page)
  {
    if ($page === '/') {
      $page = $this->getStandartPage()->url;
    }

    if (!$page) {
      return [];
    }

    $query = <<<SQL
SELECT *
FROM `pages` p
LEFT JOIN `template` t ON t.group = p.template
LEFT JOIN `modules` m ON m.module_name = p.module
WHERE p.url = :URL
SQL;

    $result = $this->adapter->getRow(
      $query,
      [
        ':URL' => $page
      ]
    );

    if (empty($result)) {
      return [];
    }

    if ($result['tpl']) {
      $result['tpl'] = explode(", ", $result['tpl']);
    }

    if ($result['js']) {
      $result['js'] = explode(", ", $result['js']);
    }

    return $result;
  }

  abstract public function route();
}

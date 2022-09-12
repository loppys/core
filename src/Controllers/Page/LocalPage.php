<?php

namespace Vengine\Controllers\Page;

use Vengine\Controllers\AbstractController;
use Vengine\Controllers\PageControllerInterface;
use Vengine\Controllers\Page\DataPageTransformer;
use Vengine\Database\Adapter;

class LocalPage extends AbstractController implements PageControllerInterface
{
  protected $pageList = array();

  private $tmp = array();
  private $start = false;

  public function __construct(DataPageTransformer $data, Adapter $adapter)
  {
    parent::__construct($adapter);
  }

  private function standartScheme(): array
  {
    $load = $this->adapter->getAll('SELECT * FROM `pages`');

    if (!$load) {
      return [];
    }

    foreach (array_shift($load) as $key => $value) {
      if ($key === 'id') {
        continue;
      }

      $result[] = $key;
    }

    return $result;
  }

  public function process()
  {
  }

  public function add(array $page): LocalPage
  {
    if ($page) {
      $this->pageList[$page['url']] = $page;
    }

    $this->transformer->dataSet($page);

    return $this;
  }

  public function startPrepare(string $url): LocalPage
  {
    $this->tmp = $this->transformer->getScheme();

    if (!empty($url)) {
      $this->tmp['url'] = $url;
      $this->start = true;
    }

    return $this;
  }

  public function setProperty(string $name, string $value): LocalPage
  {
    if (array_key_exists($name, $this->tmp)) {
      $this->tmp[$name] = $value;
    }

    return $this;
  }

  public function setProperties(array $properties): LocalPage
  {
    foreach ($properties as $key => $value) {
      if (array_key_exists($key, $this->tmp)) {
        $this->tmp[$key] = $value;
      }
    }

    return $this;
  }

  public function create(): LocalPage
  {
    if ($this->start) {
      $this->add($this->tmp);

      unset($this->tmp);
    }

    return $this;
  }

  public function delete(string $name): void
  {
    if (array_key_exists($this->pageList[$name])) {
      unset($this->pageList[$name]);
    }
  }

  public function getList(): array
  {
    return $this->pageList;
  }
}

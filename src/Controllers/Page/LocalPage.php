<?php

namespace Vengine\Controllers\Page;

use Vengine\Controllers\AbstractController;
use Vengine\Controllers\PageControllerInterface;
use Vengine\Controllers\Page\DataPageTransformer;
use Vengine\Database\Adapter;

class LocalPage extends AbstractController implements PageControllerInterface
{
  protected $pageList = array();
  private $adapter;

  public function __construct(DataPageTransformer $data)
  {
    parent::__construct($data);

    $this->adapter = new Adapter();

    if (empty($data->scheme)) {
      $this->transformer->addScheme($this->standartScheme());
    }
  }

  private function standartScheme(): array
  {
    $load = $this->adapter->getAll('SELECT * FROM `pages` WHERE 1=1');

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

  public function process(?array $data)
  {
  }

  public function add(array $page): object
  {
    if ($page) {
      $this->pageList[$page['url']] = $page;
    }

    $this->transformer->dataSet($page);

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

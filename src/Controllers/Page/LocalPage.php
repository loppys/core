<?php

namespace Vengine\Controllers\Page;

use Vengine\Controllers\AbstractController;
use Vengine\Controllers\PageControllerInterface;
use Vengine\Controllers\Page\DataPageTransformer;

class LocalPage extends AbstractController implements PageControllerInterface
{
  protected $pageList = array();

  public function __construct(DataPageTransformer $data)
  {
    parent::__construct($data);
    
    if (empty($data->scheme)) {
      $this->transformer->addScheme([
        'name',
        'page',
        'file',
        'class',
        'module',
        'url',
        'path',
        'custom_url',
        'type_tpl',
        'tpl',
        'js',
        'param_cls',
        'module_cst',
        'design',
        'param',
        'default',
        'method',
        'type',
      ]);
    }
  }

  public function process(?array $data)
  {
    // d($data);
  }

  public function add(array $page): object
  {
    if ($page) {
      $this->pageList[$page['name']] = $page;
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

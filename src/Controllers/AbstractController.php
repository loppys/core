<?php

namespace Vengine\Controllers;

use Vengine\Database\Adapter;

abstract class AbstractController
{
  protected $adapter;

  protected $data;

  function __construct(Adapter $adapter)
  {
    $this->adapter = $adapter;
  }

  abstract public function process();
}

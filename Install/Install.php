<?php

namespace Vengine\CRUD;

use Vengine\Database\Adapter;

class Install
{
  function __construct()
  {
    require 'template/form.php';
  }

  public function connectDatabase(): void
  {
    // code...
  }
}

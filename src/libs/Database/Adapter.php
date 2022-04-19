<?php

namespace Vengine\libs\Database;

use Vengine\libs\Database\AbstractAdapter;

class Adapter extends AbstractAdapter
{
  protected function connect(array $param): void
  {
    if (!$this->testConnection()) {
      parent::setup($param['connect'], $param['login'], $param['password']);
    }
  }
}

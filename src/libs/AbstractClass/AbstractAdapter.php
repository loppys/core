<?php

namespace Vengine\libs\AbstractClass;

use Vengine\Injectable;
use RedBeanPHP\R;

abstract class AbstractAdapter extends R implements Injectable
{
  /**
   * @var array
   */
  private $param;

  public function __construct()
  {
    $this->param = require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/database.php');

    if (!empty($this->param)) {
      $type = $this->param['type'];
      $host = $this->param['host'];
      $dbname = $this->param['dbname'];
      $login = $this->param['login'];
      $password = $this->param['password'];

      unset($this->param);
      $this->param['connect'] = $type . ':' . 'host=' . $host . ';' . 'dbname=' . $dbname;
      $this->param['login'] = $login;
      $this->param['password'] = $password;
    }

    $this->connect($this->param);
  }

  /**
   *
   * @param array $param
   *
   * @return return void
   */
  abstract protected function connect(array $param): void;
}

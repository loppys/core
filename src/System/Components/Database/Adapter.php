<?php

namespace Vengine\System\Components\Database;

use RedBeanPHP\R;

class Adapter extends R
{
  /**
   * @var array
   */
  private $param;

  public function __construct()
  {
    $this->param = require_once($_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

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
  }

  public function connect(): void
  {
    $param = $this->param;

    if (!$this->testConnection()) {
      parent::setup($param['connect'], $param['login'], $param['password']);
    }
  }

  public function save($table = null, array $fields = []): void
  {
    if ($table && $fields) {
          $db = parent::dispense($table);

          foreach ($fields as $keyField => $fieldValue) {
            if ($fieldValue) {
              $db->$keyField = $fieldValue;
            }
            continue;
          }

          parent::store($db);
    }
  }

  public function condition($condition)
  {
    if ($condition) {
      parent::exec( $condition );
    }
  }
}

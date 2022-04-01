<?php

namespace Vengine\libs\DataBase;

use RedBeanPHP\R;

class Adapter extends R
{
  public static function connect($connect_string = null, $dblogin = null, $dbpassword = null): void
  {
    if (!empty($connect_string)) {
			parent::setup( $connect_string, $dblogin, $dbpassword );
		}
  }

  public static function save($table = null, array $fields = []): void
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

  public static function condition($condition): void
  {
    if ($condition) {
      parent::exec( $condition );
    }
  }
}

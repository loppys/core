<?php

namespace Vengine\Database;

use Vengine\libs\Database\Adapter as ParentAdapter;

class Adapter extends ParentAdapter
{
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

  public static function staticSave($table = null, array $fields = []): void
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

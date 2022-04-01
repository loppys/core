<?php
namespace ORM;
use System\DataBase;
use R;

class RedBeanClass implements DataBase
{
  public static function connect($connect_string = null, $dblogin = null, $dbpassword = null): void
  {
    if (!empty($connect_string)) {
			R::setup( $connect_string, $dblogin, $dbpassword );
		}
  }

  public static function load($table = null, $field)
  {
    $result = R::load($table, $field);
    return $result;
  }

  public static function find($table = null, $fields, $field)
  {
    return R::findOne($table, ''.$field.' = ?', array($fields));
  }

  public static function save($table = null, array $fields = []): void
  {
    if ($table && $fields) {
          $db = R::dispense($table);

          foreach ($fields as $keyField => $fieldValue) {
            if ($fieldValue) {
              $db->$keyField = $fieldValue;
            }
            continue;
          }

          R::store($db);
    }
  }

  public static function delete()
  {

  }

  public static function condition($condition): void
  {
    if ($condition) {
      R::exec( $condition );
    }
  }
}

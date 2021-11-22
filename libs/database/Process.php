<?php
namespace ORM;
use System\DataBase;
use R;

class RedBeanClass implements DataBase
{
  public static function connect($connect_string = null, $dblogin = null, $dbpassword = null)
  {
    if (!empty($connect_string)) {
			R::setup( $connect_string, $dblogin, $dbpassword );
		}
  }

  public static function load()
  {

  }

  public static function save($table = null, array $fields = [])
  {
    if ($table && $fields) {
          $db = R::dispense($table);

          foreach ($fields as $keyField => $fieldValue) {
            $db->$keyField = $fieldValue;
            continue;
          }

          !empty($db->$keyField) ? R::store($db) : '';
    }
  }

  public static function delete()
  {

  }
}

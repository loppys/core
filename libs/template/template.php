<?php

namespace Vengine\System\libs;

/**
 * Хранит переменные для шаблонов
 */
class TemplateVar
{
  public static $var = [];

  public static function set(array $var)
  {
    if (!$var) {
      return '';
    }

    foreach ($var as $name => $value) {
      self::$var[$name] = $value;
    }
  }

  public static function get($name)
  {
    return self::$var[$name];
  }

  public static function getAll()
  {
    return self::$var;
  }
}

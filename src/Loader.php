<?php

class Loader
{
  public const TYPE_GLOBAL = 'Global';
  public const TYPE_LOCAL = 'Local';
  public const TYPE_SYSTEM = 'System';
  public const TYPE_EMPTY = 'Empty';

  public static array $modules = [];

  public static function callModule(string $name, array $param = [], bool $merge = false): ?object
  {
    $modules = self::getModules();
    $module = ['name' => $name];

    if (array_key_exists($name, $modules)) {
      $module = $modules[$name];

      if (!empty($param)) {
        if ($merge) {
          $module['param'] = array_merge($module['param'], $param);
        } else {
          $module['param'] = $param;
        }
      }
    }

    return self::getObject($module, $name);
  }

  private static function isSystem(string $type): bool
  {
    return $type === self::TYPE_SYSTEM;
  }

  private static function isGlobal(string $type): bool
  {
    return $type === self::TYPE_GLOBAL;
  }

  public static function addModule(
    string $name,
    string $type,
    string $handler = '',
    array $param = [],
    string $path = ''
  ): void {
    if (empty($type)) {
      $type = self::TYPE_EMPTY;
    }

    $module = [
      'type' => $type,
      'param' => $param,
      'handler' => $handler,
      'path' => $path
    ];

    self::$modules[$name] = $module;
  }

  public static function addModules(array $modules): void
  {
    foreach ($modules as $key => $value) {
      self::addModule(...$value);
    }
  }

  public static function getObject(array $module, string $name): ?object
  {
    if (!empty($module['object'])) {
      if (self::isSystem($module['type'])) {
        return null;
      }

      return $module['object'];
    }

    $object = null;

    if (!empty($module['type'])) {
      $class = $module['handler'];

      if (!empty($class)) {
        if (class_exists($class)) {
          $param = $module['param'];

          if ($param) {
            $object = new $class(...$param);
          } else {
            $object = new $class();
          }
        }
      }
    }

    if (!$module['object']) {
      self::$modules[$name]['object'] = $object;
    }

    return $object;
  }

  public static function getModules(): ?array
  {
    return self::$modules;
  }

  public static function getModule(string $name): ?array
  {
    return self::$modules[$name];
  }
}

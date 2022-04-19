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

    return self::getObject($module);
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
    $modules = array_merge(self::$modules, $modules);

    self::$modules = $modules;
  }

  public static function getObject(array $module): ?object
  {
    if (!empty($module['type'])) {
      $class = $module['handler'];

      if (!empty($class)) {
        if (class_exists($class)) {
          $param = $module['param'];

          if ($param) {
            return new $class(...$param);
          }

          return new $class();
        }
      }
    }

    print('<!--При загрузке модуля {' . $module['name'] . '} возникли трудности-->');

    return null;
  }

  public static function getModules(): array
  {
    return self::$modules;
  }

  public static function getModule(string $name): array
  {
    return self::$modules[$name];
  }
}

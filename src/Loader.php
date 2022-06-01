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

  public static function recreate(): void
  {
    $modules = self::getModules();

    foreach ($modules as $key => $value) {
      if (!empty($value['object']) && is_object($value['object'])) {
        unset(self::$modules[$key]['object']);

        self::callModule($key);
      } else {
        self::callModule($key);
      }
    }
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

    if (!empty($module['path'])) {
      if (file_exists($module['path'])) {
        require_once($module['path']);
      }
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
      } else {
        $object = 'object creation error';
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

  public static function getInfoModules(): array
  {
    $modules = self::$modules;
    $loaded = 'нет';
    $default = false;

    $modules['Api']['handler'] = \Vengine\Modules\Api\Process::class;

    $sysModule = scandir(__DIR__ . '/Modules');

    $info['Loader'] = [
      'name' => 'Loader',
      'version' => '---',
      'type' => 'Загрузчик',
      'loaded' => 'Всегда и всюду',
      'description' => 'Лучше не менять.'
    ];

    foreach ($modules as $key => $value) {
      if (is_object($value['object'])) {
        $loaded = 'да';
      } else {
        $loaded = 'нет';
      }

      $arr = explode('\\', $value['handler']);
      unset($arr[array_key_last($arr)]);

      $package = implode('\\', $arr);

      $class = '\\' . $package . '\\Info\\Package';

      if (class_exists($class)) {
        $object = new $class();

        $name = $object->name;
        $version = $object->version;
        $type = 'Package';

        if (in_array($name, $sysModule)) {
          $type = 'System';
          $default = true;
        }

        if ($default) {
          $loaded = 'Включен по умолчанию';
        }

        if (!empty($object->description)) {
          $description = $object->description;
        } else {
          $description = 'Описание отсутствует';
        }

        if (empty($name)) {
          $name = 'Название отсутствует';
        }

        if (empty($version)) {
          $version = '---';
        }

        $packageInfo[$key] = [
          'name' => $name,
          'version' => $version,
          'type' => $type,
          'loaded' => $loaded,
          'description' => $description,
          'all' => $object
        ];
      }
    }

    foreach ($modules as $key => $value) {
      if (!empty($packageInfo[$key])) {
        $info[$key] = $packageInfo[$key];
        continue;
      }

      $description = 'Описание отсутствует';

      $type = $value['type'];

      if (is_object($value['object'])) {
        $loaded = 'да';

        $n = $value['object']->module;
        $v = $value['object']->version;

        $name = !empty($n) ? $n : $key;
        $version = !empty($v) ? $v : '---';
      } else {
        $name = $key;
        $version = '---';
        $loaded = 'нет';
      }

      $info[$key] = [
        'name' => $name,
        'version' => $version,
        'type' => $type,
        'loaded' => $loaded,
        'description' => $description,
        'all' => $object
      ];
    }

    $info['_startup']['loaded'] = 'Включен по умолчанию';

    return $info;
  }
}

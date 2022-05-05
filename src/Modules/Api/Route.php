<?php

namespace Vengine\Modules\Api;

class Route
{
  public static function api(array $uri, array $structure): void
  {
    $class = array_shift($uri);
    $file = $class . '.php';

    $path = $structure['api'] . $class . '/' . $file;

    if (file_exists($path)) {
      require_once $path;
    } else {
      $path = $structure['uApi'] . $class . '/' . $file;

      if (file_exists($path)) {
        require_once $path;
      }
    }

    if (class_exists($class)) {
      try {
          $api = new $class();
          print $api->run();
      } catch (\Exception $e) {
        print json_encode(Array('error' => $e->getMessage()));
      }
    } else {
      header("HTTP/1.1 405 Method Not Allowed");
      print json_encode(Array('error' => 'Api not found'));
    }

    die();
  }
}

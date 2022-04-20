<?php

namespace Vengine;

use Vengine\Base;
use Vengine\Controllers\Page\LocalPage;

class Startup extends Base
{
  public function __construct()
  {
    $this->logWriter();

    parent::__construct();
  }

  public function init(?LocalPage $pages = null): void
  {
    $vendorDir = vendorDir();

    $URI = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
    $class = $URI[1];

    if (strripos($_SERVER['REQUEST_URI'], 'api') && !empty($class)) {
      $file = '/_api/'. $URI[1] .'/'. $URI[1] .'.php';
      if (file_exists($file)) {
        require_once $vendorDir . $file;
      }

      if (class_exists($class)) {
        try {
            $api = new $class();
            echo $api->run();
            die();
        } catch (Exception $e) {
            echo json_encode(Array('error' => $e->getMessage()));
        }
      } else {
        header("HTTP/1.1 405 Method Not Allowed");
        print json_encode('Api not found');
      }

      die();
    }

    //обработчик ошибок (переделать вывод)
    if ($_GET['__DEBUG'] === 'INFO') {
      $whoops = new \Whoops\Run;
      $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
      $whoops->register();
    }

    $this->run();
  }

  public function logWriter(): void
  {
    require $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

    if (!isset($config['logs']) || $config['logs'] === true) {
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 'Off');
      ini_set('log_errors', 'On');
      ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/logs/errors.log');
    }
  }
}

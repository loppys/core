<?php

namespace Vengine;

use Vengine\Base;
use Vengine\Controllers\Page\LocalPage;
use Vengine\CRUD\Install;
use Vengine\Database\Adapter;
use Vengine\Modules\Api\Route;

class Startup extends Base
{
  private $localPage;

  public function __construct(LocalPage $pages)
  {
    $this->logWriter();

    $this->localPage = $pages;

    if ($_GET['__DEBUG'] === 'INFO') {
      $whoops = new \Whoops\Run;
      $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
      $whoops->register();
    }

    parent::__construct();

    $this->init();
  }

  public function init(): void
  {
    $uri = explode('/', trim($this->interface->uri['path'], '/'));

    if (array_shift($uri) === 'api') {
      Route::api($uri, $this->interface->structure);
    }

    $this->initModules();

    \Loader::callModule('migrations');

    $this->run($this->localPage);
  }

  public function initModules(): void
  {
    $query = <<<SQL
SELECT *
FROM `modules`
SQL;

    $result = $this->adapter->getAll(
      $query
    );

    foreach ($result as $key => $value) {
      $param = explode(', ', $value['module_param']);
      \Loader::addModule(
        $value['module_name'],
        $value['module_type'],
        $value['handler'],
        $param,
      );
    }
  }

  public function logWriter(): void
  {
    require $_SERVER['DOCUMENT_ROOT'].'/../config/config.php';

    if (!isset($config['logs']) || $config['logs'] === true) {
      error_reporting(E_ALL & ~E_NOTICE);
      ini_set('display_errors', 'Off');
      ini_set('log_errors', 'On');
      ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/../logs/errors.log');
    }
  }
}

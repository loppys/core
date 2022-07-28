<?php

namespace Vengine;

use Vengine\Injectable;
use Vengine\LegacyConfig;
use Vengine\libs\Database\Adapter;
use Symfony\Component\HttpFoundation\Request;
use \Loader;

abstract class AbstractModule extends LegacyConfig
{
  /**
   * @var object
   */
  public $interface;

  /**
   * @var object
   */
  public $adapter;

  /**
   * @var object
   */
  public $request;

  /**
   * @var array
   */
  public $session;

  /**
   * @var string
   */
  public $module;

  /**
   * @var string
   */
  public $version;

  /**
   * @var string
   */
  public $url;

  function __construct()
  {
    $this->interface = new \stdClass();
    $this->setConfig();

    $defaultConfig = $this->interface->defaults;

    if ($defaultConfig) {
      if ($defaultConfig[$this->module]) {
        foreach ($defaultConfig[$this->module] as $key => $value) {
          $this->interface->$key = $value;
        }
      }

      unset($this->interface->defaults);
    }

    $this->adapter = $this->getAdapter();
    $this->request = $this->getRequest();
  }

  public function getAdapter(): Adapter
  {
    return new Adapter();
  }

  public function getRequest(): Request
  {
    return Request::createFromGlobals();
  }

  public function getInterface(): object
  {
    if (empty($this->interface)) {
      $this->interface = new \stdClass();
      $this->setConfig();
    }

    return $this->interface;
  }

  public function setConfig(): void
  {
    $config = require _File('config', '/../config');
    $coreConfig = require('config/config.php');

    if (empty($config['structure'])) {
      $config['structure'] = $this->getStandartFolderStructure();
    }

    foreach ($config as $k => $v) {
      foreach ($coreConfig as $ck => $cv) {
        if (array_key_exists($ck, $config)) {
          $config[$ck] += $cv;
        } else {
          $config[$ck] = $coreConfig[$ck];
        }
      }
    }

    $dir = $_SERVER['DOCUMENT_ROOT'];
    $projectDir = stristr($dir, 'www', true);

    $path = [
      'ROOT:' => $projectDir
    ];

    foreach ($config['structure'] as $sKey => $sValue) {
      $name = strtoupper(stristr($sValue, ':', true)) . ':';
      $tempPath = substr(stristr($sValue, ':'), 1);

      $parent = array_key_exists($name, $path);

      if ($parent) {
        $replace = [
          $name => $path[$name]
        ];

        $result = strtr($name, $replace) . $tempPath;

        $path[strtoupper($sKey) . ':'] = $result;
        $config['structure'][$sKey] = $result;
      }
    }

    if ($config['defaults']) {
      foreach ($config['defaults'] as $dk => $dv) {
        if (!$dv['require']) {
          break;
        }

        foreach ($dv['require'] as $rk => $rv) {
          $requirePath = $this->getRequirePath($rv, $config);

          if ($requirePath === 'run') {
            require($requirePath);
            continue;
          }

          $config['defaults'][$dk][$rk] = require($requirePath);
        }
      }
    }

    foreach ($config as $key => $value) {
      $this->interface->$key = $value;
    }
  }

  private function getRequirePath(array $arr, array $config): string
  {
    $path = '';

    foreach ($arr as $key => $value) {
      if (!$key) {
        $structure = false;
        break;
      }

      $path = $config['structure'][$key] . $value;
    }

    if ($structure === false) {
      return 'run';
    }

    return $path;
  }

  public function getStandartFolderStructure(): array
  {
    return [
      'project' => 'ROOT:',
      'tmp' => 'PROJECT:_tmp/',
      'www' => 'PROJECT:www/',
      'migrations' => 'PROJECT:Migrations/',
      'logs' => 'PROJECT:logs/',
      'config' => 'PROJECT:config/'
    ];
  }

  /*
  * Ошибка 404
  */
  public function error404(): void
  {
    $code = http_response_code();
    if ($code === 404) {
      exit(include 'template/error404.tpl.php');
    }
  }
}

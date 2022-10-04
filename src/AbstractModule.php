<?php

namespace Vengine;

use Vengine\Injectable;
use Vengine\LegacyConfig;
use Vengine\System\Components\Database\Adapter;
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

    $this->adapter = $this->getAdapter();
    $this->request = $this->getRequest();

    $this->setConfig();
    $this->setConfigVar();

    $defaultConfig = $this->interface->defaults;

    if ($defaultConfig) {
      if ($defaultConfig[$this->module]) {
        foreach ($defaultConfig[$this->module] as $key => $value) {
          $this->interface->$key = $value;
        }
      }

      unset($this->interface->defaults);
    }

    $this->autoload('modules');
  }

  public function getAdapter(): Adapter
  {
    return Loader::getComponent(Adapter::class);
  }

  public function getRequest(): Request
  {
    return Request::createFromGlobals();
  }

  public function autoload(string $dir): void
  {
    $dir = $this->interface->structure[$dir];

    if (!is_dir($dir)) {
      return;
    }

    $items = scandir($dir);

    foreach ($items as $item) {
      $package = $dir . $item . '/Package.php';

      if (!file_exists($package)) {
        continue;
      }

      $package = require_once($package);

      if (!is_object($package)) {
        continue;
      }

      $data = [
        'name' => $package->name,
        'group' => $package->group,
        'handler' => $package->handler,
        'param' => $package->param,
        'path' => $package->path,
        'call' => $package->call,
        'version' => $package->version,
        'description' => $package->description,
      ];

      Loader::add($data['name'], $data['group'] ?: Loader::GROUP_MODULES, $data);
    }
  }

  public function getInterface(): object
  {
    if (empty($this->interface)) {
      $this->interface = new \stdClass();
      $this->setConfig();
    }

    return $this->interface;
  }

  public function setConfigVar(): void
  {
    $query = <<<SQL
SELECT *
FROM `cfg`
SQL;

    $result = $this->adapter->getAll(
      $query
    );

    foreach ($result as $key => $value) {
      if (!$this->interface->{$value['cfg_name']}) {
        $this->interface->{$value['cfg_name']} = $value['cfg_value'];
      }
    }
  }

  public function setConfig(): void
  {
    $config = require _File('config', '/config');
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

    $projectDir = $_SERVER['DOCUMENT_ROOT'] . '/';

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
            require_once($requirePath);
            continue;
          }

          $config['defaults'][$dk][$rk] = require_once($requirePath);
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

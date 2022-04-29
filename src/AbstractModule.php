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

  function __construct()
  {
    $this->interface = new \stdClass();
    $this->adapter = $this->getAdapter();
    $this->request = $this->getRequest();

    $this->addApiModule();
  }

  public function getAdapter(): Adapter
  {
    return new Adapter();
  }

  public function addApiModule(): void
  {
    if (class_exists(\System\modules\Api::class)) {
      if (!Loader::getModule('Api')) {
        Loader::addModule(
          'Api',
          Loader::TYPE_GLOBAL,
          \System\modules\Api::class
        );
      }
    }
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

  abstract public function setConfig(): void;
}

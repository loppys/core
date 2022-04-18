<?php

namespace Vengine;

use Vengine\Injectable;
use Vengine\LegacyConfig;
use Vengine\libs\Database\Adapter;
use System\modules\Api;

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
   * @var array
   */
  public $request;

  /**
   * @var array
   */
  public $session;

  /**
   * @var string
   */
  public $api;

  function __construct()
  {
    $this->interface = new \stdClass();

    if (!Loader::getModule('Adapter')) {
      Loader::addModule(
        'Adapter',
        Loader::TYPE_SYSTEM,
        Adapter::class
      );
    }

    $this->adapter = Loader::callModule('Adapter');

    d($this->adapter);

    $this->request = $_REQUEST;
    $this->session = $_SESSION;

    if (class_exists(Api::class)) {
      $this->api = Api::class;
    }

  }

}

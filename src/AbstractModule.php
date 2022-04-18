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
    $this->adapter = new Adapter();
    $this->request = $_REQUEST;
    $this->session = $_SESSION;
    $this->api = Api::class;
  }

}

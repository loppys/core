<?php

namespace Vengine;

use Vengine\Injectable;
use Vengine\LegacyConfig;
use Vengine\libs\Database\Adapter;

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

  function __construct()
  {
    $this->interface = new \stdClass();
    $this->adapter = new Adapter();
    $this->request = $_REQUEST;
    $this->session = $_SESSION;
  }

}

<?php

class Startup
{
  public static function init()
  {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/rb.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/core/interface.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/core/Process.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/core/libs/init.php';
  }

  // public static function setConfig()
  // {
  //   // code...
  // }

  public static function initProcess()
  {
    require _File('settings', 'config');

    return new Process(
      $settings['database']['connect_string'],
      $settings['database']['login'],
      $settings['database']['password']
    );
  }
}

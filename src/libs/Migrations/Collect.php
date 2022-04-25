<?php

namespace Vengine\libs\Migrations;

use Vengine\Database\Adapter;

class Collect
{
  public array $data;

  function __construct()
  {
    $path = dirname(dirname(dirname(dirname(__FILE__)))) . '\\Migrations\\';
    $dir = scandir($path);
    unset($dir[0], $dir[1]);
    $this->set($dir, $path);
    $this->unsetСompleted();
  }

  public function set(array $dir, $path): void
  {
    foreach ($dir as $value) {
      $this->data[] = [
        'file' => $value,
        'path' => $path . $value
      ];
    }
  }

  public function unsetСompleted()
  {
    $load = Adapter::getAll('SELECT * FROM `migration` WHERE `completed` = ?', ['Y']);
    foreach ($load as $find) {
      foreach ($this->data as $key => $data) {
        if ($data['file'] == $find['file']) {
          unset($this->data[$key]);
        }
      }
    }
  }
}

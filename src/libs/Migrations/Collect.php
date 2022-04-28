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
    $info = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/composer.lock'));

    foreach ($info->packages as $key => $value) {
      if ($value->name === 'vengine/core') {
        $info = $info->packages[$key];
        break;
      }
    }

    $version = $info->version;

    foreach ($dir as $value) {
      $this->data[] = [
        'file' => $value,
        'path' => $path . $value,
        'version' => $version
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

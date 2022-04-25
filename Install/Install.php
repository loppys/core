<?php

namespace Vengine\CRUD;

use Vengine\Database\Adapter;

class Install
{
  function __construct()
  {
    $info = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/composer.lock'));

    foreach ($info->packages as $key => $value) {
      if ($value->name === 'vengine/core') {
        $info = $info->packages[$key];
      }
    }

    $version = $info->version;
    $require = $info->require;

    // $ll = system('composer require vengine-modules/api', $dd);
    $key = $_POST['key'];
    $nameProject = $_POST['nameProject'];

    if ($_POST['submit']) {
      $data = $_REQUEST;
      $this->run($data);
      echo "Запрос отправлен. Ссылка на скачивание вскоре появится в личном кабинете";
      die();
    }

    if (!empty($key) && !empty($nameProject)) {
      require 'template/form.php';
    } else {
      require 'template/auth.php';
    }
  }

  public function run($data): void
  {
    d($data);
  }

  public function connectDatabase(): void
  {
    // code...
  }
}

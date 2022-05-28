<?php

namespace Vengine\Modules\CMS\Pages;

use Vengine\Modules\CMS\AbstractPage;

class modules extends AbstractPage
{
  public $title = 'Админ-панель | Модули';

  public function index()
  {
    $modules = \Loader::getInfoModules();

    $this->content[] = $this->title . '<hr><br>';
    $this->content[] = '<br>Системные:<br>';
    foreach ($modules as $key => $value) {
      if ($value['type'] === 'System') {
        $this->content[] = "Название: {$value['name']} (Версия: {$value['version']}) <br>";

        unset($modules[$key]);
      }
    }

    $this->content[] = '<br>Другие:<br>';
    foreach ($modules as $key => $value) {
      $this->content[] = "Название: {$value['name']} (Версия: {$value['version']}) <br>";
    }
  }
}
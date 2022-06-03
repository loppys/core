<?php

namespace Vengine\Modules\CMS\Pages;

use Vengine\Modules\CMS\AbstractPage;

class modules extends AbstractPage
{
  public $title = 'Админ-панель | Модули';

  public function index()
  {
    $modules = \Loader::getInfoModules();

    foreach ($modules as $key => $value) {
        $this->data['module'][] = $value;
    }

    $this->content[] = $this->templateConnect('modules');
  }
}

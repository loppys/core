<?php

namespace Vengine\Controllers;

use Vengine\System\libs\Database\Adapter;

class FindPage
{
  public function getPage($page)
  {
    if (!$page) {
      return false;
    }

    $load = Adapter::findOne('pages', 'page = ? OR custom_url = ? OR url = ?', [$page, $page, $page]);

    if ($load->param_cls) {
      $load->param_cls = explode(", ", $load->param_cls);
    }

    if ($load->param_method) {
      $load->param_method = explode(", ", $load->param_method);
    }

    if ($load->tpl) {
      $load->tpl = explode(", ", $load->tpl);
    }

    if ($load->js) {
      $load->js = explode(", ", $load->js);
    }

  #Добавить видимость страниц в бд и добавить в проверку
    if (!empty($load)) {
      return $load;
    }else{
      return false;
    }
  }
}

<?php

function page($page)
{
  if (!$page) {
    return false;
  }
  
  $load = R::findOne('pages', 'page = ? OR custom_url = ? OR url = ?', array($page, $page, $page));

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

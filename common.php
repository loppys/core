<?php

/**
 * Все функции, которые нужны и в обычной разметке находятся тут!
 *
 * Актульно с 0.4 версии
 *
 */

/**
 * Подключает/вставляет изображение
 */
function includeImagePath($image, $insert = false)
{
  if ($image && !$insert) {
    include $_SERVER['DOCUMENT_ROOT'] . '/images/' . $image;
  }else{
    print '/images/' . $image;
  }
}

/**
 * Если нужно, то подключает только этот модуль для каких-либо целей!
 */
function includeOneModule($module)
{
  if ($module) {
    include $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $module . '.php';
  }
}

/**
 * var_dump
 */
function d($dump, $exit = false)
{
 if (!$exit) {
   var_dump($dump);
 }else{
   var_dump($dump);
   exit();
 }
}

/**
 * print_r
 */
function pr($print, $exit = false)
{
  if (!$exit) {
    print_r($dump);
  }else{
    print_r($dump);
    exit();
  }
}


/**
 * Возврат метода выбранного класса
 */
function returnMethod($class, $param_class = '', $method = '', $param_method = '', $path = '', $module = '')
{
  require $_SERVER['DOCUMENT_ROOT'] . '/config/settings.php';

  if (empty($class)) {
    return;
  }

  if (is_array($param_class)) {
    $param_class = implode(', ', $param_class);
  }

  if (is_array($param_method)) {
    $param_method = implode(', ', $param_method);
  }

  if ($path != '') {
    require $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
  }

  // if ($module) {
  //   require $_SERVER['DOCUMENT_ROOT'] . '/modules/' . $module . '.php';
  // }

  if ($param_class == 'standart') {
    $tempClass = new $class(
      $settings['database']['connect_string'],
      $settings['database']['login'],
      $settings['database']['password']
    );
  } else {
    if ($param_class) {
      $tempClass = new $class($param_class);
    } else {
      $tempClass = new $class();
    }
  }

  if (!empty($method) && !empty($param_method)) {
    $tempClass->$method($param_method);
  } elseif (!empty($method) && empty($param_method)) {
    $tempClass->$method();
  }

}

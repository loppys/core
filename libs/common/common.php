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
    print_r($print);
  }else{
    print_r($print);
    exit();
  }
}


/**
 * Переводы))
 */
function tr($text = '', $translate = '')
{
  return print $text;
}

/**
 * Возврат метода выбранного класса
 */

 //Переделать
function returnMethod($class, $param_class = '', $method = '', $param_method = '', $path = '', $module = '')
{
  require _File('settings', 'config');

  if (empty($class)) {
    return;
  }

  if (is_array($param_class) && $class != 'Controller' && $module != '$%array%$') {
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
    return $tempClass->$method($param_method);
  } elseif (!empty($method) && empty($param_method)) {
    return $tempClass->$method();
  }

  return $tempClass;

}

/*
* Поиск и только поиск файлов в указанной папке
*/
function returnFolderContents($dir, $print = false)
{
  $pageDir = $_SERVER['DOCUMENT_ROOT'] . '/' . $dir . '/';
  $pageRoad = scandir($pageDir);

  #Удаление не нужных элементов
  $f = array_slice($pageRoad, 3);

  foreach ($f as $i) {
    $fix = substr($i, strrpos($i, '.') + 1);

    if ($fix == 'php' && $i != '_helpers.php' && $i != 'Install.php') {
      if (file_exists($pageDir . $i) && $print == false) {
        return $f;
      }elseif ($print === true) {
        print substr($i, 0, -4) . '<br>';
      }
    }
  }
}

/**
 * Проверка активности модуля
 */
function isActiveModule($moduleName): bool
{
  require _File('settings', 'config');

  if (!$modules[$moduleName] || !class_exists($moduleName)) {
    return false;
  }

  return true;
}

function returnObject($class, $param_class = '')
{
  require _File('settings', 'config');

  if (class_exist($class)) {
    return !empty($param_class) ? new $class($param_class) : new $class();
  }
}

function _File($file, $path = '')
{
  if (!empty($path)) {
    $dir = $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
    return $dir.'/'.$file.'.php';
  }else{
    return $file.'.php';
  }
}

function init_controller()
{
	return $_SERVER['DOCUMENT_ROOT'] . '/core/controllers/Controller.php';
}

function api()
{
  if (!$_GET) {
    return tr('Отсутствуют парметры!');
  }

  return returnMethod('Api_Process', $_GET, '', '', '', '$%array%$');
}

function errorCode($text, $code, $module)
{
  return tr($text.' <!--'.'ERR:'.$code.'::'.$module.'-->');
}

function controller($name, $param)
{
  require_once init_controller();

  return returnMethod('Controller', '', $name, $param);
}

function errorRender($errors = [])
{
  require _File('Render.error', 'core/Render');

  if ($errors) {
    new ErrorDisplay($errors);
  }
}

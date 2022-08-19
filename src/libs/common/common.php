<?php

use Vengine\libs\Dumper;

/**
 * Все функции, которые нужны и в обычной разметке находятся тут!
 *
 * Актульно с 0.4 версии
 *
 */

/**
 * var_dump
 */
 function d(...$dump)
 {
   $debug = debug_backtrace();

   $data = array(
     'data' => $dump,
     'debug' => array(
       'file' => $debug[0]['file'] ?? null,
       'line' => $debug[0]['line'] ?? null,
       'time' => microtime(true),
     )
   );

   print '<pre style="
   color:black;
   font-size: 1.2em;
   white-space: pre-wrap;
   ">';
   print Dumper::dump($data);
   print '</pre>';

   die();
 }

 function vendorDir(): string
 {
   return dirname(dirname(__FILE__));
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
 function returnMethod($class, $param_class = '', $method = '', $param_method = '', $path = '', $module = '')
 {
   require _File('settings', 'config');

   if (empty($class)) {
     return;
   }

   if (is_array($param_class) && $module != '$%array%$') {
     $param_class = implode(', ', $param_class);
   }

   if ($path != '') {
     require $_SERVER['DOCUMENT_ROOT'] . '/' . $path;
   }

   if (!class_exists($class)) {
     return include $_SERVER['DOCUMENT_ROOT'] . '/core/template/error.tpl.php';
   }

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
  //удалить
  return false;

  require _File('settings', 'config');

  if (!$modules[$moduleName] || !class_exists($moduleName)) {
    return false;
  }

  return true;
}

function returnObject($class, $param_class = '')
{
  require _File('settings', 'config');

   if (is_array($param_class)) {
     $param_class = implode(', ', $param_class);
   }

  if (class_exists($class)) {
  	if (!empty($param_class)) {
  		return new $class($param_class);
  	} else {
  		return new $class();
  	}
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

function errorCode($text, $code, $module)
{
  return tr($text.' <!--'.'ERR:'.$code.'::'.$module.'-->');
}

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

/**
 * Переводы))
 */
function tr($text = '', $translate = '')
{
    return print $text;
}

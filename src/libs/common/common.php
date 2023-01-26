<?php

use Vengine\libs\Dumper;

/*
 * Все функции, которые нужны и в обычной разметке находятся тут!
 *
 * Актульно с 0.4 версии
 *
 */

function d(...$dump): void
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
 * @param string $login
 * @return string
 *
 * @throws Exception
 */
function getGUID(string $login = 'empty'): string
{
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }

    return sprintf(
        '%04X%04X-%04X-%04X-%04X-%04X%04X%04X:%06X',
        random_int(0, 65535),
        random_int(0, 65535),
        random_int(0, 65535),
        random_int(16384, 20479),
        random_int(32768, 49151),
        random_int(0, 65535),
        random_int(0, 65535),
        random_int(0, 34234),
        strlen(md5($login)) + random_int(0, 34234),
    );
}

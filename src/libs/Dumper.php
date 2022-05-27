<?php

namespace Vengine\libs;

class Dumper
{
    private static $objects;
    private static $output;
    private static $depth;

    public static function dump($var, $depth = 10)
    {
        self::$output = '';
        self::$objects = [];
        self::$depth = $depth;
        self::dumpInternal($var, 0);

        return self::$output;
    }

    private static function dumpInternal($var, $level)
    {
        switch (gettype($var)) {
            case 'boolean':
                self::$output .= $var ? 'true' : 'false';
                break;
            case 'integer':
                self::$output .= (string)$var;
                break;
            case 'double':
                self::$output .= (string)$var;
                break;
            case 'string':
                if (substr_count($var, "'")) {
                    if (!substr_count($var, '"')) {
                        self::$output .= '"' . $var . '"';
                    } else {
                        self::$output .= "'" . addslashes($var) . "'";
                    }
                } else {
                    self::$output .= "'" . $var . "'";
                }
                break;
            case 'resource':
                self::$output .= '{resource}';
                break;
            case 'NULL':
                self::$output .= 'null';
                break;
            case 'unknown type':
                self::$output .= '{unknown}';
                break;
            case 'array':
                if (self::$depth <= $level) {
                    self::$output .= '[...]';
                } elseif (empty($var)) {
                    self::$output .= '[]';
                } else {
                    $keys = array_keys($var);
                    $spaces = str_repeat(' ', $level * 4);
                    self::$output .= '[';
                    foreach ($keys as $key) {
                        self::$output .= "\n" . $spaces . '    ';
                        self::dumpInternal($key, 0);
                        self::$output .= ' => ';
                        self::dumpInternal($var[$key], $level + 1);
                    }
                    self::$output .= "\n" . $spaces . ']';
                }
                break;
            case 'object':
                if (($id = array_search($var, self::$objects, true)) !== false) {
                    self::$output .= get_class($var) . '#' . ($id + 1) . '(...)';
                } elseif (self::$depth <= $level) {
                    self::$output .= get_class($var) . '(...)';
                } else {
                    $id = array_push(self::$objects, $var);
                    $className = get_class($var);
                    $spaces = str_repeat(' ', $level * 4);
                    self::$output .= "$className#$id\n" . $spaces . '(';
                    $dumpValues = (array)$var;
                    foreach ($dumpValues as $key => $value) {
                        $keyDisplay = str_replace("\0", ':', trim($key));
                        self::$output .= "\n" . $spaces . "    [{$keyDisplay}] => ";
                        self::dumpInternal($value, $level + 1);
                    }
                    self::$output .= "\n" . $spaces . ')';
                }
                break;
        }
    }
}

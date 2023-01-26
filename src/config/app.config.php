<?php

use Vengine\App;
use Vengine\System\Controllers\Router;

return [
    [
        'name' => App::getName(),
        'className' => App::getClassName(),
        'defaultMethod' => 'init'
    ],
    [
        'name' => Router::getName(),
        'className' => Router::getClassName(),
        'defaultMethod' => 'handle'
    ],
];

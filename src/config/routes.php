<?php

use Vengine\System\Settings\Storages\MethodType;
use Vengine\System\Components\Page\Home\HomePageController;

return [
    [
        'route' => '/',
        'method' => MethodType::GET,
        'handler' => HomePageController::class,
    ],
];

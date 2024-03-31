<?php

use Vengine\System\Settings\Storages\AccessLevelStorage;
use Vengine\System\Components\Page\Home\HomePageController;

return [
    [
        'route' => '/',
        'handler' => [
            'controller' => HomePageController::class,
            'method' => 'indexAction',
            'access' => AccessLevelStorage::ALL
        ]
    ],
];

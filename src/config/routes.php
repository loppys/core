<?php

use Vengine\System\Settings\Storages\AccessLevelStorage;
use Vengine\System\Components\Page\Home\HomePageController;
use Vengine\libs\Helpers\DebugInfo;

return [
    [
        'route' => '/',
        'handler' => [
            'controller' => HomePageController::class,
            'method' => 'indexAction',
            'access' => AccessLevelStorage::ALL
        ]
    ],
    [
        'route' => '/~root/debug/',
        'handler' => [
            'controller' => DebugInfo::class,
            'method' => 'indexAction',
            'access' => AccessLevelStorage::ALL
        ]
    ],
];

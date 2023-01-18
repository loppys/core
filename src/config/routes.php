<?php

use Vengine\System\Settings\Storages\MethodType;
use Vengine\Packages\Updater\Components\RenderUpdater;
use Vengine\System\Components\Page\Home\HomePageController;
use Vengine\System\Settings\Storages\AccessLevelStorage;

return [
    [
        'route' => '/home/',
        'method' => MethodType::GET,
        'handler' => HomePageController::class,
    ],
    [
        'route' => '/install/',
        'method' => MethodType::GET,
        'handler' => [
            'controller' => RenderUpdater::class,
            'method' => 'process',
            'access' => AccessLevelStorage::ALL
        ]
    ],
    [
        'route' => '/install/step/{num:\d+}',
        'method' => MethodType::GET,
        'handler' => [
            'controller' => RenderUpdater::class,
            'method' => 'changeStep',
            'access' => AccessLevelStorage::ROOT
        ]
    ],
];

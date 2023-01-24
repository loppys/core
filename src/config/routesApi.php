<?php

use Vengine\Api\GUID\GuidController;
use Vengine\System\Settings\Storages\MethodType;

return [
    [
        'route' => '/user/guid/generate/[{login}]',
        'method' => MethodType::POST,
        'handler' => [
            'controller' => GuidController::class,
            'method' => 'indexAction'
        ]
    ],
];

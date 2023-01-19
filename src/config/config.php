<?php

return [
    'structure' => [
        'vendor' => 'PROJECT:vendor/',
        'loader' => 'PROJECT:vendor/vengine/container/',
        'core' => 'PROJECT:vendor/vengine/core/',
        'modules' => 'CORE:src/Modules/',
        'coreConfig' => 'CORE:src/config/',
        'userConfig' => 'PROJECT:config/',
        'api' => 'CORE:src/_api/',
        'uApi' => 'WWW:_api/'
    ],
    'defaults' => [
        'Core' => [
            'closed' => false,
            'require' => [
                'appConfig' => ['coreConfig' => 'app.config.php']
            ]
        ],
    ]
];

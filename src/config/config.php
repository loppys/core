<?php

return [
    'structure' => [
        'vendor' => 'PROJECT:vendor/',
        'container' => 'PROJECT:vendor/vengine/container/',
        'core' => 'PROJECT:vendor/vengine/core/',
        'coreConfig' => 'CORE:src/config/',
        'userConfig' => 'PROJECT:config/',
        'api' => 'CORE:src/_api/',
        'uApi' => 'WWW:_api/'
    ],
    'defaults' => [
        'Startup' => [
            'closed' => false,
        ],
    ]
];

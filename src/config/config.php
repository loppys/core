<?php

return [
    'structure' => [
        'vendor' => 'PROJECT:vendor/',
        'loader' => 'PROJECT:vendor/vengine/loader/',
        'core' => 'PROJECT:vendor/vengine/core/',
        'modules' => 'CORE:src/Modules/',
        'coreConfig' => 'CORE:src/config/',
        'api' => 'CORE:src/_api/',
        'uApi' => 'WWW:_api/'
    ],
    'defaults' => [
        'Core' => [
            'closed' => false,
            'require' => [
                'project' => ['coreConfig' => 'project.config.php'],
                'pages' => ['coreConfig' => 'routes.php']
            ]
        ],
    ]
];

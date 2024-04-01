<?php

use Vengine\App;
use Vengine\System\Config\AppConfig;
use Vengine\System\Interfaces\AppConfigInterface;
use Vengine\System\Controllers\Router;
use Vengine\AbstractConfig;
use Vengine\System\Interfaces\AbstractPropertyInterface;
use Vengine\Packages\Migrations\Interfaces\AdapterSQLInterface;
use Vengine\Packages\Migrations\Adapters\AdapterSQL;
use Vengine\Packages\Migrations\Interfaces\AdapterPHPInterface;
use Vengine\Packages\Migrations\Adapters\AdapterPHP;
use Vengine\Packages\Migrations\Interfaces\MigrationManagerInterface;
use Vengine\Packages\Migrations\MigrationManager;

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
    'alias' => [
        AppConfig::class => AppConfigInterface::class,
        AbstractConfig::class => AbstractPropertyInterface::class,
        AdapterSQL::class => AdapterSQLInterface::class,
        AdapterPHP::class => AdapterPHPInterface::class,
        MigrationManager::class => MigrationManagerInterface::class,
    ],
];

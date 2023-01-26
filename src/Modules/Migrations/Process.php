<?php

namespace Vengine\Modules\Migrations;

use Vengine\AbstractModule;
use Vengine\libs\Migrations\Query;

/**
 * @deprecated
 */
class Process extends AbstractModule
{
    public $module = 'Migrations';
    public $version = '1.0.7';

    public function __construct()
    {
        parent::__construct();

        $this->container->createObject(Query::class);
    }
}

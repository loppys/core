<?php

namespace Vengine\Modules\Migrations;

use Vengine\AbstractModule;
use Vengine\libs\Migrations\Query;
use Vengine\libs\Migrations\Collect;

class Process extends AbstractModule
{
    public $module = 'Migrations';
    public $version = '1.0.7';

    public function __construct()
    {
        parent::__construct();

        $collect = new Collect($this->structure);

        if (!empty($collect->data)) {
            new Query($collect);
        }
    }
}

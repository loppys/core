<?php

namespace Vengine\Packages\Migrations\Parts;

use Vengine\App;
use Vengine\Packages\Migrations\DTO\MigrationResult;
use Vengine\System\Database\SystemAdapter;
use Vengine\System\Exceptions\AppException;

abstract class Migration
{
    protected SystemAdapter $databaseAdapter;

    protected MigrationResult $result;

    /**
     * @throws AppException
     */
    public function __construct()
    {
        $this->databaseAdapter = App::app()->adapter;

        $this->result = new MigrationResult();

        $baseFile = pathinfo(static::class)['basename'];
        $baseFile = strstr($baseFile, ':', true);

        $this->result->setFile($baseFile);
    }

    abstract public function run(): MigrationResult;
}

<?php

use Vengine\Packages\Migrations\DTO\MigrationResult;
use Vengine\Packages\Migrations\Parts\Migration;

// Пример реализации PHP миграции
return new class extends Migration
{
    public function run(): MigrationResult
    {
        $this->result->setDescription('Пример миграции');

        return $this->result;
    }
};
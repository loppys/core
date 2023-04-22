<?php

namespace Vengine\Packages\Migrations\Interfaces;

interface MigrationAdapterInterface
{
    public function run(array $fileList): MigrationAdapterInterface;

    public function getResult(): array;
}

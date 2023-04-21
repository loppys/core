<?php

namespace Vengine\Packages\Migrations\Interfaces;

interface MigrationManagerInterface
{
    public function run(): void;

    public function check(): MigrationManagerInterface;

    public function setAdapter(MigrationAdapterInterface $adapter): MigrationManagerInterface;

    public function getAdapter(): MigrationAdapterInterface;
}

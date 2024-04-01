<?php

namespace Vengine\System\Interfaces;

interface AbstractPropertyInterface
{
    public function __get(string $name): mixed;

    public function __isset($name): bool;

    public function __set(string $name, $value): void;

    public function __unset(string $name): void;

    public function getPropertyList(): array;
}

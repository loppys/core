<?php

namespace Vengine\System\Interfaces;

interface AbstractPropertyInterface
{
    public function __get(string $name);

    public function __isset($name);

    public function __set(string $name, $value);

    public function __unset(string $name);

    public function getPropertyList(): array;
}

<?php

namespace Vengine;

abstract class AbstractConfig
{
    private $property = [];

    public function __get(string $name)
    {
        return $this->property[$name] ?? null;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->property);
    }

    public function __set(string $name, $value)
    {
        $this->property[$name] = $value;
    }

    public function __unset(string $name)
    {
        unset($this->property[$name]);
    }
}

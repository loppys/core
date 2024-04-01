<?php

namespace Vengine;

use Vengine\System\Interfaces\AbstractPropertyInterface;

class AbstractConfig implements AbstractPropertyInterface
{
    private array $property = [];

    public function __get(string $name): mixed
    {
        return $this->property[$name] ?? null;
    }

    public function __isset($name): bool
    {
        return array_key_exists($name, $this->property);
    }

    public function __set(string $name, $value): void
    {
        $this->property[$name] = $value;
    }

    public function __unset(string $name): void
    {
        unset($this->property[$name]);
    }

    public function getPropertyList(): array
    {
        return $this->property;
    }
}

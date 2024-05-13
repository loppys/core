<?php

namespace Vengine\Packages\Modules;

use Vengine\Packages\Modules\Interfaces\InfoInterface;
use Vengine\Packages\Modules\Storage\TypeStorage;

class ModuleInfo implements InfoInterface
{
    protected string $name = '';

    protected string $version = '';

    protected string $description = '';

    protected bool $system = false;

    protected string $developer = '';

    protected int $type = TypeStorage::COMMON;

    protected bool $loaded = false;

    public function __construct(string $name)
    {
        $this->setName($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isSystem(): bool
    {
        return $this->system;
    }

    public function getDeveloper(): string
    {
        return $this->developer;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function isLoaded(): bool
    {
        return $this->loaded;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function setDeveloper(string $developer): static
    {
        $this->developer = $developer;

        return $this;
    }

    public function setType(int $type): static
    {
        if ($type === TypeStorage::SYSTEM) {
            $this->system = true;
        }

        $this->type = $type;

        return $this;
    }

    public function setLoaded(): static
    {
        $this->loaded = true;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }
}

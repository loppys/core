<?php

namespace Vengine\Packages\Modules\Interfaces;

interface InfoInterface
{
    public function __construct(string $name);

    public function getName(): string;

    public function getVersion(): string;

    public function getDescription(): string;

    public function isSystem(): bool;

    public function getDeveloper(): string;

    public function getType(): int;

    public function isLoaded(): bool;

    public function setDescription(string $description): static;

    public function setDeveloper(string $developer): static;

    public function setType(int $type): static;

    public function setLoaded(): static;

    public function setName(string $name): static;

    public function setVersion(string $version): static;
}

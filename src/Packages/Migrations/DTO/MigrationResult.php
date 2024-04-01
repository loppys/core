<?php

namespace Vengine\Packages\Migrations\DTO;

class MigrationResult
{
    protected string $file = '';

    protected string $error = '';

    protected string $version = '';

    protected string $description = '';

    public function getFile(): string
    {
        return $this->file;
    }

    public function setFile(string $file): MigrationResult
    {
        $this->file = $file;

        return $this;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): MigrationResult
    {
        $this->error = $error;

        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): MigrationResult
    {
        $this->version = $version;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): MigrationResult
    {
        $this->description = $description;

        return $this;
    }
}

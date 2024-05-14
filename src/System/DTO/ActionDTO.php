<?php

namespace Vengine\System\DTO;

use Vengine\System\Actions;

class ActionDTO
{
    protected string $name;

    protected array $functionList = [];

    protected string $controller = '';

    protected array $accessList = [];

    protected int $type = Actions::TYPE_COMMON;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ActionDTO
    {
        $this->name = $name;

        return $this;
    }

    public function getFunctionList(): array
    {
        return $this->functionList;
    }

    public function setFunctionList(array $functionList): ActionDTO
    {
        $this->functionList = $functionList;

        return $this;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): ActionDTO
    {
        $this->controller = $controller;

        return $this;
    }

    public function getAccessList(): array
    {
        return $this->accessList;
    }

    public function setAccessList(array $accessList): ActionDTO
    {
        $this->accessList = $accessList;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): ActionDTO
    {
        $this->type = $type;

        return $this;
    }
}

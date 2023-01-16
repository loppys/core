<?php

namespace Vengine\System\Components\Page;

use Render\Engine\Data\Manager;
use Render\Engine\Factory\RenderFactory;
use Render\Engine\Storage\DataStorage;
use Loader;

class Render
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var DataStorage
     */
    private $dataStorage;

    /**
     * @var Render
     */
    private static $instance;

    public function __construct()
    {
        $this->manager = Loader::getComponent(Manager::class);
        $this->dataStorage = Loader::getComponent(DataStorage::class);
    }

    public static function getInstance(): self
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function render(): void
    {
        $this->getRenderFactory()->render();
    }

    public function runTemplate(string $tpl): void
    {
        $this->getRenderFactory()->runTemplate($tpl);
    }

    public function getRenderFactory(): RenderFactory
    {
        return Loader::getComponent(RenderFactory::class);
    }

    public function getHead(): string
    {
        return $this->manager->getHead();
    }

    public function addMetaData(string $name, string $value): Manager
    {
        return $this->manager->addMetaData($name, $value);
    }

    public function setTitle(string $title): Manager
    {
        return $this->manager->setTitle($title);
    }

    public function getTitle(): string
    {
        return $this->manager->getTitle();
    }

    public function addTemplate(string $path): Manager
    {
        return $this->manager->addTemplate($path);
    }

    public function addTemplateList(array $pathList): Manager
    {
        return $this->manager->addTemplateList($pathList);
    }

    public function setTemplateList(array $pathList, bool $merge = true): Manager
    {
        return $this->manager->setTemplateList($pathList, $merge);
    }

    public function initJsByName(string $name, string $script, bool $skipPage = false): Manager
    {
        return $this->manager->initJsByName($name, $script, $skipPage);
    }

    public function initJs(string $script, bool $skipPage = false): Manager
    {
        return $this->manager->initJs($script, $skipPage);
    }

    public function initJsPath(string $path): Manager
    {
        return $this->manager->initJsPath($path);
    }

    public function getJsInfoByName(string $name): array
    {
        return $this->manager->getJsInfoByName($name);
    }

    public function addStyle(string $path): Manager
    {
        return $this->manager->addStyle($path);
    }

    public function setVariableByName(string $name, $value): DataStorage
    {
        return $this->dataStorage->setVariableByName($name, $value);
    }

    public function addVariable($value): DataStorage
    {
        return $this->dataStorage->addVariable($value);
    }

    public function getVariableByName(string $name)
    {
        return $this->dataStorage->getVariableByName($name);
    }

    public function getVariableList(): array
    {
        return $this->dataStorage->getVariableList();
    }

    public function deleteVariableByName(string $name): DataStorage
    {
        return $this->dataStorage->delete($name);
    }
}
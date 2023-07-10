<?php

namespace Vengine\System\Components\Page;

use Loader\System\Container;
use Render\Engine\Data\Manager;
use Render\Engine\DataStorageInterface;
use Render\Engine\DefaultManager;
use Render\Engine\Factory\RenderFactory;
use Render\Engine\Storage\DataStorage;
use Vengine\App;

class Render
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var DataStorage
     */
    protected $dataStorage;

    /**
     * @var RenderFactory
     */
    protected $renderFactory;

    /**
     * @var Render
     */
    private static $instance;

    public function __construct(
        Manager $manager,
        DataStorage $dataStorage,
        RenderFactory $renderFactory
    ) {
        $this->manager = $manager;
        $this->dataStorage = $dataStorage;
        $this->renderFactory = $renderFactory;
    }

    public static function getInstance(): self
    {
        if (empty(static::$instance)) {
            static::$instance = Container::getInstance()->createObject(static::class);
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
        return $this->renderFactory;
    }

    public function getTemplateFolder(): string
    {
        return $this->manager->getTemplateFolder();
    }

    public function setTemplateFolder(string $path): DefaultManager
    {
        return $this->manager->setTemplateFolder($path);
    }

    public function getHead(): string
    {
        return $this->manager->getHead();
    }

    public function addMetaData(string $name, string $value, string $content): Manager
    {
        return $this->manager->addMetaData($name, $value, $content);
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

    public function addStyle(string $path, string $other = ''): Manager
    {
        return $this->manager->addStyle($path, $other);
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

    public function deleteVariableByName(string $name): DataStorageInterface
    {
        return $this->dataStorage->delete($name);
    }
}

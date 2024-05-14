<?php

namespace Vengine\Modules\Debug;

use Vengine\AbstractModule;
use Vengine\libs\Cache;
use Vengine\Packages\Modules\ModuleInfo;
use Vengine\Packages\Modules\Interfaces\InfoInterface;
use Vengine\Packages\Modules\Storage\TypeStorage;
use Vengine\System\Components\Page\Render;
use Vengine\System\Exceptions\AccessDeniedException;
use ReflectionException;

class Main extends AbstractModule
{
    protected string $module = '_debug_';

    protected string $version = 'public';

    /**
     * @throws ReflectionException
     * @throws AccessDeniedException
     */
    public function process(): static
    {
        if (!password_verify($this->request->get('pass') ?? '', $this->configurator->getConfig()['app']['root'])) {
            throw new AccessDeniedException('Доступ закрыт. Пароль от root пользователя неверный.');
        }

        $tplPath = __DIR__ . '/tpl/debug.php';

        $moduleList = [];

        $link = <<<HTML
<a href="https://vengine.ru/">loppys</a>
HTML;

        $moduleList[] = (new ModuleInfo('Core'))
            ->setType(TypeStorage::SYSTEM)
            ->setVersion($this->getPackageVersion())
            ->setDescription('Собственно ядро!')
            ->setDeveloper($link)
            ->setLoaded()
        ;

        $moduleList[] = (new ModuleInfo('DI'))
            ->setType(TypeStorage::SYSTEM)
            ->setVersion($this->getPackageVersion('container'))
            ->setDescription('Автоматический подхват зависимостей')
            ->setDeveloper($link)
            ->setLoaded()
        ;

        $renderInfo = (new ModuleInfo('Render'))
            ->setType(TypeStorage::SYSTEM)
            ->setVersion($this->getPackageVersion('render'))
            ->setDeveloper($link)
        ;

        if ($this->isCreated(Render::class)) {
            $renderInfo->setLoaded();
        }

        $moduleList[] = $renderInfo;

        $cacheInfo = (new ModuleInfo('Cache'))
            ->setType(TypeStorage::SYSTEM)
            ->setVersion($this->getPackageVersion('cache'))
            ->setDeveloper($link)
        ;

        if ($this->isCreated(Cache::class)) {
            $cacheInfo->setLoaded();
        }

        $moduleList[] = $cacheInfo;

        foreach (get_declared_classes() as $item) {
            $parent = get_parent_class($item);

            if (!empty($parent) && $parent === AbstractModule::class) {
                $moduleList[] = $this->container->createObject($item)?->getInfo();
            }
        }

        $libs = [];
        foreach ($this->getAllLibs() as $nl => $vl) {
            if ($nl === '__root__') {
                continue;
            }

            $libs[] = [
                'name' => $nl,
                'version' => $vl['pretty_version'] ?? $vl['version']
            ];
        }

        $config = $this->configurator->getConfig();

        $dbType = $config['database']['dbType'];
        $dbHost = $config['database']['dbHost'];
        $dbName = $config['database']['dbName'];
        $dbLogin = $config['database']['dbLogin'];

        require_once $tplPath;

        return $this;
    }

    public function render(): void
    {
    }

    private function getPackageVersion(string $package = 'core'): string
    {
        return $this->getAllLibs()["vengine/{$package}"]['pretty_version'] ?? '';
    }

    private function getAllLibs(): array
    {
        $versionPath = $this->structure->vendor . '/composer/installed.php';

        if (file_exists($versionPath)) {
            $info = require($versionPath);
        }

        return $info['versions'] ?? [];
    }

    private function isCreated(string $class): bool
    {
        return $this->container->getObjectStorage()->has(strtolower($class));
    }

    public function changeModuleInfo(InfoInterface $info): void
    {
        $info
            ->setDeveloper('loppys')
            ->setName('D:root')
            ->setType(TypeStorage::SYSTEM)
        ;
    }
}

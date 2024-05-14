<?php

namespace Vengine\libs;

use Loader\System\Container;
use Vengine\Cache\CacheManager;
use Vengine\Cache\config\Configurator;
use Vengine\Cache\Exceptions\BuildConfigException;
use Vengine\Cache\Exceptions\UniqueOptionException;
use Vengine\Cache\Storage\DriverStorage;

class Cache
{
    protected bool $disabled = false;

    private CacheManager $cacheManager;

    public function __construct(Container $container)
    {
        $this->cacheManager = $container->createObject(
            CacheManager::class,
            [
                $container->createObject(Configurator::class)
            ]
        );
    }

    public function getCacheManager(): CacheManager
    {
        return $this->cacheManager;
    }

    /**
     * @throws BuildConfigException
     * @throws UniqueOptionException
     */
    public function disable(): static
    {
        $this->disabled = true;

        $this->changeEnabled(false);

        return $this;
    }

    /**
     * @throws BuildConfigException
     * @throws UniqueOptionException
     */
    public function enable(): static
    {
        $this->disabled = false;

        $this->changeEnabled(true);

        return $this;
    }

    /**
     * @throws BuildConfigException
     * @throws UniqueOptionException
     */
    private function changeEnabled(bool $enabled): void
    {
        $allDrivers = DriverStorage::DEFAULT_DRIVERS + DriverStorage::SPECIFIC_DRIVERS;
        if ($this->disabled) {
            foreach ($allDrivers as $name => $class) {
                $this->cacheManager->createDriver($name)?->getConfig()->setEnabled($enabled);
            }
        }
    }
}

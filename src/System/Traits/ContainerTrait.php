<?php

namespace Vengine\System\Traits;

use Vengine\System\Config\AppConfig;
use Vengine\System\Interfaces\AppConfigInterface;

trait ContainerTrait
{
    use \Loader\System\ContainerTrait;

    /** @noinspection MagicMethodsValidityInspection */
    public function __get($name): mixed
    {
        if (property_exists(AppConfig::class, $name)) {
            /** @var AppConfig $config */
            $config = $this->getContainer()->createObject(AppConfigInterface::class);

            return $config->{$name};
        }

        return $this->getContainer()->getShared($name);
    }
}

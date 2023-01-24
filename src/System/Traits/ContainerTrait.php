<?php

namespace Vengine\System\Traits;

use Vengine\AppConfig;

trait ContainerTrait
{
    use \Loader\System\ContainerTrait;

    /** @noinspection MagicMethodsValidityInspection */
    public function __get($name)
    {
        if (property_exists(AppConfig::class, $name)) {
            /** @var AppConfig $config */
            $config = $this->getContainer()->createObject(AppConfig::class);

            return $config->{$name};
        }

        return $this->getContainer()->getShared($name);
    }
}

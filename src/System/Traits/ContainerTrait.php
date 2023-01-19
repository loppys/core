<?php

namespace Vengine\System\Traits;

trait ContainerTrait
{
    use \Loader\System\ContainerTrait;

    /** @noinspection MagicMethodsValidityInspection */
    public function __get($name)
    {
        return $this->getContainer()->getShared($name);
    }
}

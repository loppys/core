<?php

namespace Vengine\System\Interfaces;

use Vengine\Packages\Modules\Interfaces\InfoInterface;

interface ModuleInterface
{
    public function getVersion(): string;

    public function getModuleName(): string;

    public function changeModuleInfo(InfoInterface $info): void;

    public function getInfo(): InfoInterface;
}

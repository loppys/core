<?php

namespace Vengine;

use Vengine\System\Interfaces\AppConfigInterface;

class AppConfig extends AbstractConfig implements AppConfigInterface
{
    public $closed = false;

    public function getAllProperty(): array
    {
        return get_object_vars($this) + $this->getPropertyList();
    }
}

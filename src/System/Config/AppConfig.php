<?php

namespace Vengine\System\Config;

use Vengine\System\Interfaces\AppConfigInterface;
use Vengine\AbstractConfig;

class AppConfig extends AbstractConfig implements AppConfigInterface, AwareConfigPropertyInterface
{
    public bool $closed = false;

    /**
     * Будет использоваться стандартное значение, если нет токена в базе
     */
    public string $token = 'fae0b27c451c728867a567e8c1bb4e53';

    public function getAllProperty(): array
    {
        return get_object_vars($this) + $this->getPropertyList();
    }
}

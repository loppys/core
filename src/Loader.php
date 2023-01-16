<?php

use Vengine\System\Cache;
use Vengine\Packages\Settings\Storages\ConstStorage;
use Loader\Builder\Storage;
use Loader\Process;

class Loader extends Process
{
    public static function getCacheObject(string $name): ?Cache
    {
        if (ConstStorage::CACHE_ENABLED !== true) {
            return null;
        }

        $fullName = md5('cache' . $name);

        if (Storage::has($fullName)) {
            return parent::callModule($fullName);
        }

        $data = [
            'name' => $fullName,
            'handler' => \Vengine\System\Cache::class,
            'param' => [$name],
        ];

        Storage::add($fullName, Storage::GROUP_COMPONENT, $data);

        return self::getCacheObject($name);
    }
}

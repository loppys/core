<?php

namespace Vengine;

use Vengine\Modules\Migrations\Process;
use Vengine\System\Components\Database\Adapter;
use Vengine\Modules\Api\Route;
use Vengine\System\Traits\ContainerTrait;
use ReflectionException;

class Startup implements Injection
{
    use ContainerTrait;

    public function __construct()
    {
        $this->container = $this->getContainer();
    }

    public function run(): void
    {
        /** @TODO полностью переделать */
        App::app()->createObject(Process::class);

        $this->initModules();

        $this->base->run();
    }

    public function initModules(): bool
    {
        $query = <<<SQL
SELECT *
FROM `modules`
SQL;

        $result = $this->adapter::getAll(
            $query
        );

        return $this->container->packageCollect($result);
    }
}

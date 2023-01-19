<?php

namespace Vengine;

use Vengine\Modules\Migrations\Process;

final class Startup extends AbstractModule
{
    public function run(): void
    {
        /** @TODO полностью переделать */
        App::app()->createObject(Process::class);

        $this->initModules();

        $this->collectModuleRoutes();

        $this->router->handle();
    }

    protected function initModules(): bool
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

    protected function collectModuleRoutes(): void
    {
        $query = <<<SQL
SELECT *
FROM `routes`
SQL;

        $routes = $this->adapter::getAll($query);

        $routes = array_map(static function ($item) {
            return [
                'route' => $item['route'],
                'method' => $item['request_method'],
                'handler' => [
                    'controller' => $item['controller'],
                    'method' => $item['method'],
                    'access' => (int)$item['access']
                ]
            ];
        }, $routes);

        $this->router->addRouteList($routes);
    }
}

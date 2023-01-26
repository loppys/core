<?php

namespace Vengine;

use Vengine\Modules\Migrations\Process;
use Vengine\System\Settings\Storages\PermissionType;

/**
 * @property bool closed
 */
final class Startup extends AbstractModule
{
    public $module = 'Startup';

    public function run(): void
    {
        if ($this->closed && $this->user->getRole() !== PermissionType::DEVELOPER) {
            die('На сайте ведутся технические работы, попробуйте вернуться позже!');
        }

        /** @TODO полностью переделать */
        App::app()->createObject(Process::class);

        $this->initModules();

        $this->collectRoutesFromDatabase();

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

    protected function collectRoutesFromDatabase(): void
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

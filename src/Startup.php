<?php

namespace Vengine;

use Vengine\Packages\Modules\Interfaces\InfoInterface;
use Vengine\Packages\Modules\Storage\TypeStorage;

/**
 * @property bool closed
 */
final class Startup extends AbstractModule
{
    protected string $module = 'Startup';

    protected string $version = '3FPR';

    /**
     * @return void
     *
     * @throws System\Exceptions\AccessDeniedException
     * @throws System\Exceptions\MethodNotAllowedException
     * @throws System\Exceptions\PageNotFoundException
     */
    public function run(): void
    {
        if ($this->closed && $this->user->isDeveloper()) {
            die('На сайте ведутся технические работы, попробуйте вернуться позже!');
        }

        $this->initModules();

        $this->collectRoutesFromDatabase();

        $this->initModule('_debug_', Main::class);

        $this->router->handle();
    }

    protected function initModules(): bool
    {
        $query = <<<SQL
SELECT *
FROM `modules`
SQL;

        return $this->container->packageCollect(
            $this->db->executeQuery($query)->fetchAllAssociative()
        );
    }

    protected function collectRoutesFromDatabase(): void
    {
        $query = <<<SQL
SELECT *
FROM `routes`
SQL;

        $routes = $this->db->executeQuery($query)->fetchAllAssociative();

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

    public function changeModuleInfo(InfoInterface $info): void
    {
        $info
            ->setDeveloper('<a href="https://vengine.ru/">loppys</a>')
            ->setDescription('Модуль запуска приложения')
            ->setType(TypeStorage::SYSTEM)
        ;
    }
}

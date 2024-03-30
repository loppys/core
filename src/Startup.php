<?php

namespace Vengine;

use Vengine\System\Settings\Storages\PermissionType;

/**
 * @property bool closed
 */
final class Startup extends AbstractModule
{
    public string $module = 'Startup';

    /**
     * @return void
     *
     * @throws System\Exceptions\AccessDeniedException
     * @throws System\Exceptions\MethodNotAllowedException
     * @throws System\Exceptions\PageNotFoundException
     */
    public function run(): void
    {
        if ($this->closed && $this->user->getRole() !== PermissionType::DEVELOPER) {
            die('На сайте ведутся технические работы, попробуйте вернуться позже!');
        }

        $this->migrationManager->run();

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

        return $this->container->packageCollect(
            $this->db->getConnection()->executeQuery($query)->fetchAllAssociative()
        );
    }

    protected function collectRoutesFromDatabase(): void
    {
        $query = <<<SQL
SELECT *
FROM `routes`
SQL;

        $routes = $this->db->getConnection()->executeQuery($query)->fetchAllAssociative();

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

<?php

namespace Vengine\System;

use Vengine\App;
use Vengine\Cache\Drivers\FileDriver;
use Vengine\Injection;
use Vengine\System\Controllers\Router;
use Vengine\System\DTO\ActionDTO;
use Vengine\System\Exceptions\AccessDeniedException;
use Vengine\System\Settings\Permissions;
use Vengine\System\Settings\Storages\PermissionType;
use Vengine\System\Traits\ContainerTrait;

class Actions implements Injection
{
    use ContainerTrait;

    public const TYPE_SYSTEM = 00001;
    public const TYPE_COMMON = 00002;

    private array $actionList = [
        'ExampleAction' => [
            'fn' => [
                'example',
                'example',
                'example',
            ],
            'controller' => 'ExampleController',
            'access' => [
                PermissionType::USER,
                PermissionType::ADMIN,
                PermissionType::ROOT,
                PermissionType::SYSTEM,
                PermissionType::DEVELOPER,
            ],
            'type' => self::TYPE_SYSTEM
        ],
    ];

    private Permissions $permissions;

    private FileDriver $fileDriver;

    public function __construct(Permissions $permissions)
    {
        $this->permissions = $permissions;
        $this->container = $this->getContainer();
    }

    public function addAction(ActionDTO $action): Actions
    {
        if (!empty($this->getAction($action->getName()))) {
            return $this;
        }

        $this->actionList[$action->getName()] = [
            'fn' => $action->getFunctionList(),
            'controller' => $action->getController(),
            'access' => array_merge([PermissionType::SYSTEM], $action->getAccessList()),
            'type' => $action->getType(),
        ];

        return $this;
    }

    public function removeAction(string $name): bool
    {
        $action = $this->getAction($name);

        if (empty($action)) {
            return false;
        }

        if ($action['type'] === self::TYPE_SYSTEM) {
            return false;
        }

        unset($this->actionList[$name]);

        return true;
    }

    public function getAction(string $name): array
    {
        return $this->actionList[$name] ?: [];
    }

    /**
     * @param string $subj
     * @param string $fn
     *
     * @throws AccessDeniedException
     */
    public function handle(string $subj, string $fn): void
    {
        $action = $this->getAction($subj);
        $user = $this->permissions->getUserEntity();

        $request = App::getRequest();

        if (!empty($action)) {
            if (!in_array($user->getRole(), $action['access'], true)) {
                throw new AccessDeniedException();
            }

            if (!in_array($fn, $action['fn'], true)) {
                Router::redirect(
                    App::getRequest()->getPathInfo()
                );
            }

            if (empty($action['controller'])) {
                return;
            }

            $data = [];

            if (!empty($request->getContent())) {
                $data['body'] = $request->getContent();
            }

            $post = $request->request->all();

            if (!empty($post)) {
                $data['post'] = $post;
            }

            $get = $request->query->all();

            if (!empty($get)) {
                $data['get'] = $get;
            }

            $this->container->getBuilder()->invoke(
                $this->container->createObject($action['controller']),
                $fn,
                [$data]
            );
        }
    }

    public function getPermissions(): Permissions
    {
        return $this->permissions;
    }
}

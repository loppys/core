<?php

namespace Vengine\System;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Vengine\System\Exceptions\AccessDeniedException;
use Vengine\System\Settings\Storages\PermissionType;
use Vengine\System\Controllers\Router;
use Vengine\App;

abstract class DefaultController
{
    protected App $app;

    protected Request $request;

    protected SessionInterface $session;

    protected Router $router;

    protected Actions $actions;

    public function __construct()
    {
        $this->app = $app = App::app();

        $this->router = $app->router;
        $this->actions = $app->container->createObject(Actions::class);

        $this->request = App::getRequest();
        $this->session = $this->request->getSession();
    }

    /**
     * @param string $subj
     * @param string $fn
     *
     * @throws AccessDeniedException
     */
    protected function forceHandleAction(string $subj, string $fn): void
    {
        $userEntity = $this->actions->getPermissions()->getUserEntity();
        $role = $userEntity->getRole();

        $userEntity->setRole(PermissionType::SYSTEM);

        $this->actions->handle($subj, $fn);

        $userEntity->setRole($role);
    }

    protected function redirect(string $path): void
    {
        $this->router::redirect($path);
    }
}

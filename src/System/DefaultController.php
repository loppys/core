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
    /**
     * @var App
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Actions
     */
    protected $actions;

    public function __construct()
    {
        $this->app = $app = App::app();

        $this->router = $app->router;
        $this->actions = $app->createObject(Actions::class);

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

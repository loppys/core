<?php

namespace Vengine\Packages\User\Factory;

use Loader\System\Container;
use Vengine\App;
use Vengine\Packages\User\Entity\User;

class UserFactory
{
    public static function create(): void
    {
        $container = Container::getInstance();
        $session = App::getSession();

        /** @var User $user */
        $user = $container->createObject(User::class);

        if ($session->isStarted() && $session->has('user')) {
            $userInfo = $session->get('user');

            if ($userInfo instanceof User) {
                $user->setRole($userInfo->getRole())
                    ->setLogin($userInfo->getLogin())
                    ->setToken($userInfo->getToken())
                    ->setId($userInfo->getId());
            }
        }

        $session->set('user', $user);

        $container->setShared('user', $user);
    }
}

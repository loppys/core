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

        if ($session->isStarted() && $session->has('user')) {
            $userInfo = $session->get('user');

            /** @var User $user */
            $user = $container->createObject(User::class);

            if ($userInfo instanceof User) {
                $user->setRole($userInfo->getRole())
                    ->setLogin($userInfo->getLogin())
                    ->setToken($userInfo->getToken())
                    ->setUuid($userInfo->getUuid())
                    ->setId($userInfo->getId());
            }

            $session->set('user', $user);
        } else {
            /** @var User $user */
            $user = $container->createObject(User::class);

            $session->set('user', $user);
        }

        $container->setShared('user', $user);
    }

    public static function getUser(): ?User
    {
        $user = null;

        $session = App::getSession();

        if ($session->isStarted() && $session->has('user')) {
            $userEntity = $session->get('user');

            if ($userEntity instanceof User)
            {
                $user = $userEntity;
            }
        }

        return $user;
    }

    public static function saveUser(User $user): void
    {
        App::getSession()->set('user', $user);
    }
}

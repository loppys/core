<?php

namespace Vengine\System\Settings;

use Vengine\Packages\User\Entity\User;
use Vengine\System\Settings\Storages\AccessLevelStorage;
use Vengine\System\Settings\Storages\PermissionType;

class Permissions
{
    /**
     * @var User
     */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function checkAccess(int $accessLevel): bool
    {
        switch (true) {
            case $accessLevel === AccessLevelStorage::ALL:
                return true;
                break;
            case $accessLevel === AccessLevelStorage::GUEST && $this->userIsGuest():
                return true;
                break;
            case $accessLevel === AccessLevelStorage::USER && $this->isUser():
                return true;
                break;
            case $accessLevel === AccessLevelStorage::ADMIN && $this->userIsAdmin():
                return true;
                break;
            case $accessLevel === AccessLevelStorage::ROOT && $this->userIsRoot():
                return true;
                break;
            case $accessLevel === AccessLevelStorage::API && $this->defaultSystemCheck():
                return true;
                break;
        }

        return false;
    }

    public function getUserEntity(): User
    {
        return $this->user;
    }

    public function defaultSystemCheck(): bool
    {
        return $this->userIsSystem() || $this->userIsRoot() || $this->userIsDeveloper();
    }

    public function userIsSystem(): bool
    {
        return $this->user->getRole() === PermissionType::SYSTEM;
    }

    public function userIsGuest(): bool
    {
        return $this->user->getRole() === PermissionType::GUEST;
    }

    public function userIsAdmin(): bool
    {
        return $this->user->getRole() === PermissionType::ADMIN;
    }

    public function userIsDeveloper(): bool
    {
        return $this->user->getRole() === PermissionType::DEVELOPER;
    }

    public function userIsRoot(): bool
    {
        return $this->user->getRole() === PermissionType::ROOT;
    }

    public function isUser(): bool
    {
        return $this->user->getRole() === PermissionType::USER;
    }
}

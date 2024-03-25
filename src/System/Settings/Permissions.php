<?php

namespace Vengine\System\Settings;

use Vengine\Packages\User\Entity\User;
use Vengine\System\Settings\Storages\AccessLevelStorage;
use Vengine\System\Settings\Storages\PermissionType;

class Permissions
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function checkAccess(int $accessLevel): bool
    {
        return match (true) {
            $accessLevel === AccessLevelStorage::ALL => true,
            $accessLevel === AccessLevelStorage::GUEST && $this->userIsGuest() => true,
            $accessLevel === AccessLevelStorage::USER && $this->isUser() => true,
            $accessLevel === AccessLevelStorage::ADMIN && $this->userIsAdmin() => true,
            $accessLevel === AccessLevelStorage::ROOT && $this->userIsRoot() => true,
            $accessLevel === AccessLevelStorage::API && $this->defaultSystemCheck() => true,
            default => false,
        };
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

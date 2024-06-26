<?php

namespace Vengine\Packages\User\Entity;

use Vengine\System\Settings\Storages\PermissionType;

/**
 * @deprecated
 * @see В разработке отдельный модуль
 */
class User
{
    private int $id = 0;

    protected int $role = PermissionType::GUEST;

    protected string $login = '';

    protected string $token = '';

    protected string $uuid = '';

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): User
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getRole(): int
    {
        return $this->role;
    }

    public function setRole(int $role): User
    {
        $this->role = $role;

        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): User
    {
        $this->login = $login;

        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): User
    {
        $this->token = $token;

        return $this;
    }

    public function isDeveloper(): bool
    {
        return $this->role === PermissionType::DEVELOPER;
    }

    public function isRoot(): bool
    {
        return $this->role === PermissionType::ROOT;
    }

    public function isGuest(): bool
    {
        return $this->role === PermissionType::GUEST;
    }

    public function isAdmin(): bool
    {
        return $this->role === PermissionType::ADMIN;
    }
}

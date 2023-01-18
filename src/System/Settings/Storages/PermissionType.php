<?php

namespace Vengine\System\Settings\Storages;

class PermissionType
{
    //для внутренних операций, где необходим просто пользователь
    public const SYSTEM = 1;

    //Имеют доступ к просмотру ошибок прямо в браузере
    public const ROOT = 2;
    public const DEVELOPER = 3;

    //Стандартные роли
    public const ADMIN = 4;
    public const USER = 5;
    public const GUEST = 6;
}
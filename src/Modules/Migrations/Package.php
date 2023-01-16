<?php

return new class()
{
    public $name = 'migrations';
    public $handler = \Vengine\Modules\Migrations\Process::class;
    public $group = Loader::GROUP_SYSTEM;
};

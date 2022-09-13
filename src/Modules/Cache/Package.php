<?php

return new class()
{
  public $name = 'cache';
  public $handler = \Vengine\Modules\Cache\Cache::class;
  public $group = Loader::GROUP_MODULES;
};

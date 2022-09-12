<?php

return new class()
{
  public $name = 'settings';
  public $handler = \Vengine\Modules\Settings\Process::class;
  public $group = Loader::GROUP_MODULES;
};

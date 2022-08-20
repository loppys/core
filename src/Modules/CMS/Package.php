<?php

return new class()
{
  public $name = 'CMS';
  public $version = '0.1.0';
  public $description = 'бла-бла-бла';
  public $group = Loader::GROUP_SYSTEM;
  public $handler = \Vengine\Modules\CMS\Main::class;
};

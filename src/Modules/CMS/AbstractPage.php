<?php

namespace Vengine\Modules\CMS;

abstract class AbstractPage
{
  protected $url = '';

  public $content = [];

  public $title;
  public $access = 'Admin';

  public $visible = true;

  abstract public function index();

  public function getRenderData(): array
  {
    return $this->content;
  }
}

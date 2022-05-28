<?php

namespace Vengine\Modules\CMS;

abstract class AbstractPage
{
  protected $url = '';

  public $content = [];

  public $title;
  public $access = 'Admin';

  public $visible = true;

  public $data;

  abstract public function index();

  public function getRenderData(): array
  {
    $this->content[] = $this;

    return $this->content;
  }

  public function templateConnect($template)
  {
    $dir = dirname(__FILE__);

    $template = 'file::' . $dir . '/Pages/tpl/' . $template;

    return $template;
  }
}

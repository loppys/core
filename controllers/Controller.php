<?php

/**
 * Все контроллеры тут!
 */
class Controller
{

  public function page($param)
  {
    require _File('Page', 'core/controllers/page');

    return page($param);
  }

}

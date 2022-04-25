<?php

namespace Vengine\libs\Migrations;

class Collection
{
  function __construct()
  {
    d(dirname(dirname(__FILE__)));
    scandir(dirname(dirname(__FILE__)));
  }
}

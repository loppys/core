<?php

namespace System\common;

class Keygen
{
  //временно
  public static function new(string $data): string
  {
    return md5($data);
  }
}

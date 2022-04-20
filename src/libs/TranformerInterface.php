<?php

namespace Vengine\libs;

interface TranformerInterface
{
  public function addScheme(array $scheme);

  public function dataFormat();

  public function get();
}

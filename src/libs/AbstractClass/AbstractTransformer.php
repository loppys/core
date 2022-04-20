<?php

namespace Vengine\libs\AbstractClass;

use Vengine\libs\TranformerInterface;

abstract class AbstractTransformer implements TranformerInterface
{
  public $scheme;

  public $data;

  function __construct($scheme = [])
  {
    if (!empty($scheme)) {
      foreach ($scheme as $key => $value) {
        $this->scheme[$value] = $value;
      }
    }
  }

  public function addScheme(array $scheme)
  {
    if (!empty($this->scheme)) {
      return $this;
    }

    foreach ($scheme as $value) {
      $this->scheme[$value] = $value;
    }

    return $this;
  }

  public function dataSet($data)
  {
    $this->data = $data;

    return $this;
  }

  public function get()
  {
    return $this->dataFormat();
  }

  abstract public function dataFormat();
}

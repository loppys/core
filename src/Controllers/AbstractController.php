<?php

namespace Vengine\Controllers;

use Vengine\libs\AbstractClass\AbstractTransformer;

abstract class AbstractController
{
  protected $scheme;
  protected $data;

  protected $transformer;

  function __construct($transformer)
  {
    if ($transformer instanceof AbstractTransformer) {

      if (empty($this->scheme)) {
        $this->scheme = $transformer->scheme;
      }

      $this->data = $transformer->data;
      $this->transformer = $transformer;

      $this->process($transformer->get());
    }
  }

  abstract public function process(?array $transformer);
}

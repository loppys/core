<?php

namespace Vengine\Controllers\Page;

use Vengine\libs\AbstractClass\AbstractTransformer;

class DataPageTransformer extends AbstractTransformer
{
  public function dataFormat()
  {
    switch (gettype($this->data)) {
      case 'array':
        $result = array_replace($this->scheme, $this->data);
        return $result;
        break;

      default:
        return null;
        break;
    }
  }
}

<?php

namespace Vengine\System\Exceptions;

use Throwable;

class MethodNotAllowedException extends PageNotFoundException
{
    public function __construct($message = "_", $code = 405, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

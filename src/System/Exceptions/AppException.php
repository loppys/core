<?php

namespace Vengine\System\Exceptions;

use Exception;
use Throwable;

class AppException extends Exception
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
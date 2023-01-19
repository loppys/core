<?php

namespace Vengine\System\Exceptions;

use Throwable;

class AccessDeniedException extends AppException
{
    public function __construct($message = "", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
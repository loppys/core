<?php

namespace Vengine\System\Exceptions;

use Exception;
use Throwable;

class AppException extends Exception
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        http_response_code($code);
        if (!empty($message)) {
            print json_encode(['message' => $message, 'code' => $code], JSON_THROW_ON_ERROR);
        }

        parent::__construct($message, $code, $previous);
    }
}

<?php

namespace Vengine\libs\Exception;

use Vengine\libs\Exception\Http;

class HttpException extends \Exception
{
    /**
     * @var int status code (404, 500 ...)
     */
    public $status;

    public function __construct($status, $message = null, $code = 0, \Throwable $previous = null)
    {
        $this->status = $status;
        if (!$message) {
            $message = Http::getMessage($status);
        }

        parent::__construct($message, $code, $previous);
    }
}

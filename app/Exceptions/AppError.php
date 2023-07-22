<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class AppError extends Exception
{
    protected $message, $httpCode, $shouldLog;

    public function __construct(string $message, int $httpCode = 400, bool $shouldLog = true)
    {
        $this->message = $message;
        $this->httpCode = $httpCode;
        $this->shouldLog = $shouldLog;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function getShouldLog(): int
    {
        return $this->shouldLog;
    }
}
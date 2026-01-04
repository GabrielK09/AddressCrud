<?php

namespace App\Exceptions\NotFoundExceptions\User;

use RuntimeException;

abstract class UserException extends RuntimeException
{
    protected $statusCode = 400;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

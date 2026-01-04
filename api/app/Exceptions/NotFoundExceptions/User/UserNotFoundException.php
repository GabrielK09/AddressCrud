<?php

namespace App\Exceptions\NotFoundExceptions\User;

use Throwable;

class UserNotFoundException extends UserException
{
    protected $statusCode = 400;

    public function __construct(
        string $message = "", 
        int $code = 0,
        Throwable|null $previous = null
    ){
        parent::__construct($message);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
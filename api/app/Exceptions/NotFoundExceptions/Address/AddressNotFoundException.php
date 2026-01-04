<?php

namespace App\Exceptions\NotFoundExceptions\Address;

use Throwable;

class AddressNotFoundException extends AddressException
{
    protected $statusCode = 404;

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
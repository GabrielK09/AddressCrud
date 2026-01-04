<?php

namespace App\Exceptions\NotFoundExceptions\Address;

use RuntimeException;

abstract class AddressException extends RuntimeException
{
    protected $statusCode = 400;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}

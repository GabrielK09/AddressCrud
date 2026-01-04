<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if($e instanceof ValidationException)
        {
            return apiError(
                $e->getMessage(),
                $e->errors(),
                false,
                422
            );
        }

        if($e instanceof \App\Exceptions\QueryExceptions\QueryException)
        {
            return apiError($e->getMessage() ?? 'Erro na consulta do banco de dados', [], false, 400);
        }

        if($e instanceof \App\Exceptions\NotFoundExceptions\Address\AddressNotFoundException)
        {
            return apiError($e->getMessage(), [], false, $e->getStatusCode());
        }

        if($e instanceof \Illuminate\Auth\AuthenticationException)
        {
            return apiError('Usuário não autenticado.', [], false, 401);
        }
        
        Log::channel('errors')->error('Erro detectado:', [
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'code' => $e->getCode(),
            'error' => $e->getMessage()
        ]);

        return apiError($e->getMessage(), [], false, 500);
    }
}
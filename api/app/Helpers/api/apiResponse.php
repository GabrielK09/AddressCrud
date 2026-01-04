<?php

function apiSuccess(string $message = 'Sucesso!', mixed $data = [], bool $success = true, int $status = 200)
{
    return response()->json([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'status' => $status
        
    ], $status);
};

function apiError(string $message, mixed $data = [], bool $success = false, int $status = 400)
{
    return response()->json([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'status' => $status
        

    ], $status);
};
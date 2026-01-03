<?php

function apiSuccess($message = 'Sucesso!', $data = [], $success = true, $status = 200)
{
    return response()->json([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'status' => $status
        
    ], $status);
};

function apiError($message, $data = [], $success = false, $status = 400)
{
    return response()->json([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'status' => $status
        

    ], $status);
};
<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function() {
    Route::prefix('/auth')->group(function() {
        Route::controller(AuthController::class)->group(function() {
            Route::post('/login', 'login');
            Route::post('/register', 'register');
            Route::post('/logout', 'logout');

            Route::prefix('/user')->group(function() {
                Route::controller(UserController::class)->group(function() {
                    Route::put('/update/user-data', 'update');
                    Route::delete('/cancel-account', 'destroy');
    
                });
            });
        });
    });

    Route::middleware('auth:sanctum')->group(function() { 
        require_once __DIR__.'/address/addressApi.php';
        
    });
});
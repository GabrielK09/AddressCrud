<?php

use App\Http\Controllers\Address\AddressController;
use Illuminate\Support\Facades\Route;

Route::prefix('/address')->group(function () {
    Route::controller(AddressController::class)->group(function() {
        Route::get('/index', 'index');
        Route::post('/store-full-data', 'storeFullData');
        Route::post('/store-by-cep', 'storeByCep');
        Route::get('/show-{address_id}-address', 'show');
        Route::get('/consult-cep/{cep}', 'speedFetch');
        Route::put('/update/{address_id}', 'update');
        Route::delete('/remove/{address_id}', 'destroy');
    });
});
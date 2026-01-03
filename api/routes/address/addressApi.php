<?php

use App\Http\Controllers\Address\AddressController;
use Illuminate\Support\Facades\Route;

Route::prefix('/address')->group(function () {
    Route::controller(AddressController::class)->group(function() {
        Route::get('/index/{user_id}', 'index');
        Route::post('/store-full-data', 'storeFullData');
        Route::post('/store-by-cep', 'storeByCep');
        Route::get('/show-{user_id}-{address_id}-cep', 'show');
        Route::get('/consult-cep/{cep}', 'speedFetch');
        Route::put('/update/{address_id}', 'update');
        Route::put('/update/set-main-address/{user_id}/{address_id}', 'setMainAddress');
        Route::delete('/remove/{user_id}/{address_id}', 'destroy');
    });
});
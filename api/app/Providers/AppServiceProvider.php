<?php

namespace App\Providers;

use App\Services\Address\AddressService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\Address\AddressContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\Address\AddressContract::class,
            \App\Repositories\Eloquent\Address\AddressRepository::class
        );

        $this->app->singleton(AddressService::class, function() {
            return new AddressService(
                config('address.api_url'),
                $this->app->make(AddressContract::class)
                
            );
        });

        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \App\Exceptions\Handler::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

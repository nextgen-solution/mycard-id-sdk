<?php

namespace NextgenSolution\MyCardIDSDK;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Service::class, function ($app) {
            return new Service(new Client(), config('services.mycard'));
        });

        Auth::provider('mycard', function ($app) {
            return new UserProvider();
        });
    }
}
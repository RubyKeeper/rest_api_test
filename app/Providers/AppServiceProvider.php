<?php

namespace App\Providers;

use App\Clients\DadataClient;
use App\Clients\RamisClient;
use App\Clients\WarmsClient;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\ClientsHttpInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ClientsHttpInterface::class, function () {
            return new WarmsClient();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

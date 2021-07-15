<?php

namespace App\Providers;

use App\Services\flights\FlightsServices;
use App\Services\flights\FlightsServicesInterface;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            FlightsServicesInterface::class,
            FlightsServices::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (App::environment() === "production") {
            resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme('https');
        }
    }
}

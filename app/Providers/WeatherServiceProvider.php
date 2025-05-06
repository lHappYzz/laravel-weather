<?php

namespace App\Providers;

use App\Weather\Contracts\WeatherProviderInterface;
use App\Weather\Providers\WeatherApiProvider;
use Illuminate\Support\ServiceProvider;

class WeatherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            WeatherProviderInterface::class,
            WeatherApiProvider::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace Zdearo\Meli;

use Illuminate\Support\ServiceProvider;
use Zdearo\Meli\Support\ApiClient;
use Zdearo\Meli\Support\MeliApiClient;

class MeliServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/meli.php', 'meli'
        );

        $this->app->singleton('meli.client', function ($app) {
            return new MeliApiClient();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/meli.php' => config_path('meli.php'),
        ], 'meli-config');
    }
}

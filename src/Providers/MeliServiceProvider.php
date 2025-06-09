<?php

namespace Zdearo\Meli\Providers;

use Illuminate\Support\ServiceProvider;
use Zdearo\Meli\Meli;
use Zdearo\Meli\Http\MeliClient;

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

        $this->app->singleton(MeliClient::class, function ($app) {
            $config = $app['config']['meli'];
            return new MeliClient(
                $config['api_token'] ?? '',
                $config['timeout'] ?? 10.0
            );
        });

        $this->app->singleton(Meli::class, function ($app) {
            $config = $app['config']['meli'];
            return new Meli(
                $config['region'] ?? 'BRASIL',
                $config['api_token'] ?? '',
                $config['timeout'] ?? 10.0
            );
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

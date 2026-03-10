<?php

namespace Zdearo\Meli;

use Illuminate\Support\ServiceProvider;
use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Services\AuthService;
use Zdearo\Meli\Services\CategoryService;
use Zdearo\Meli\Services\DocumentationService;
use Zdearo\Meli\Services\NotificationService;
use Zdearo\Meli\Services\OrderService;
use Zdearo\Meli\Services\PaymentService;
use Zdearo\Meli\Services\ProductService;
use Zdearo\Meli\Services\QuestionService;
use Zdearo\Meli\Services\SearchItemService;
use Zdearo\Meli\Services\UserService;
use Zdearo\Meli\Services\VisitsService;
use Zdearo\Meli\Support\McpClient;
use Zdearo\Meli\Support\MeliApiClient;

class MeliServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/meli.php', 'meli'
        );

        $this->app->singleton('meli', fn ($app) => new MeliApiClient);

        $this->app->bind(AuthService::class);
        $this->app->bind(CategoryService::class);
        $this->app->bind(NotificationService::class);
        $this->app->bind(OrderService::class);
        $this->app->bind(PaymentService::class);
        $this->app->bind(ProductService::class);
        $this->app->bind(QuestionService::class);
        $this->app->bind(UserService::class);
        $this->app->bind(VisitsService::class);

        $this->app->bind(SearchItemService::class, function ($app) {
            $region = MarketplaceEnum::{config('meli.region')};

            return new SearchItemService($region);
        });

        $this->app->bind(DocumentationService::class, function ($app) {
            $token = $app->make('meli')->resolveAccessToken() ?? config('meli.api_token');

            return new DocumentationService(new McpClient($token));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/meli.php' => config_path('meli.php'),
        ], 'meli-config');
    }
}

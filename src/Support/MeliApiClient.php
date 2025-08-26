<?php

namespace Zdearo\Meli\Support;

use Illuminate\Http\Client\PendingRequest;
use Zdearo\Meli\Services\AuthService;
use Zdearo\Meli\Services\CategoryService;
use Zdearo\Meli\Services\NotificationService;
use Zdearo\Meli\Services\OrderService;
use Zdearo\Meli\Services\PaymentService;
use Zdearo\Meli\Services\ProductService;
use Zdearo\Meli\Services\QuestionService;
use Zdearo\Meli\Services\SearchItemService;
use Zdearo\Meli\Services\UserService;
use Zdearo\Meli\Services\VisitsService;

class MeliApiClient extends ApiClient
{
    public static function getAuthUrl($state): string
    {
        $redirectUri = config('meli.redirect_uri');
        $clientId = config('meli.client_id');
        $authDomain = config('meli.auth_domain', 'mercadolibre.com.br');

        return "https://auth.{$authDomain}/authorization?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}";
    }

    public function auth(): AuthService
    {
        return app(AuthService::class);
    }

    public function products(): ProductService
    {
        return app(ProductService::class);
    }

    public function searchItem(): SearchItemService
    {
        return app(SearchItemService::class);
    }

    public function visits(): VisitsService
    {
        return app(VisitsService::class);
    }

    public function users(): UserService
    {
        return app(UserService::class);
    }

    public function categories(): CategoryService
    {
        return app(CategoryService::class);
    }

    public function orders(): OrderService
    {
        return app(OrderService::class);
    }

    public function questions(): QuestionService
    {
        return app(QuestionService::class);
    }

    public function notifications(): NotificationService
    {
        return app(NotificationService::class);
    }

    public function payments(): PaymentService
    {
        return app(PaymentService::class);
    }

    protected function baseUrl(): string
    {
        return config('meli.base_url');
    }

    protected function authorize(PendingRequest $request): PendingRequest
    {
        return $request->withToken(config('meli.api_token'));
    }
}

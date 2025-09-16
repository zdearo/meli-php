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
    protected ?string $contextualToken = null;
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
        $token = $this->resolveAccessToken();

        if ($token) {
            return $request->withToken($token);
        }

        return $request;
    }

    public function withToken(string $token): static
    {
        $this->contextualToken = $token;
        return $this;
    }

    public function forConnection($connection): static
    {
        if (is_object($connection) && property_exists($connection, 'access_token')) {
            $this->contextualToken = $connection->access_token;
        }
        elseif (is_object($connection) && method_exists($connection, 'getAccessToken')) {
            $this->contextualToken = $connection->getAccessToken();
        }
        elseif (is_string($connection) || is_int($connection)) {
            $tokenResolver = config('meli.access_token_resolver');
            if (is_callable($tokenResolver)) {
                $this->contextualToken = call_user_func($tokenResolver, $connection);
            }
        }

        return $this;
    }

    protected function resolveAccessToken(): ?string
    {
        if ($this->contextualToken) {
            return $this->contextualToken;
        }

        $tokenResolver = config('meli.access_token_resolver');

        if (is_callable($tokenResolver)) {
            return call_user_func($tokenResolver);
        }

        if (is_string($tokenResolver) && class_exists($tokenResolver)) {
            $resolver = app($tokenResolver);
            if (method_exists($resolver, 'resolve')) {
                return $resolver->resolve();
            }
        }

        return config('meli.api_token');
    }
}

<?php

namespace Zdearo\Meli\Support;

use Illuminate\Http\Client\PendingRequest;

class MeliApiClient extends ApiClient
{
    public static function getAuthUrl(): string
    {
        $redirectUri = config('meli.redirect_uri');
        $clientId = config('meli.client_id');
        $state = static::generateState();
        $authDomain = config('meli.auth_domain', 'mercadolibre.com.br');

        return "https://auth.{$authDomain}/authorization?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}";
    }

    public static function generateState(): string
    {
        return bin2hex(random_bytes(16));
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

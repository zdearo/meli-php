<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Services\BaseService;
use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Http\MeliClient;

class AuthService extends BaseService
{
    private string $uri;

    public function __construct(MarketplaceEnum $region, MeliClient $client)
    {
        parent::__construct($client);
        $this->uri = $region->domain();
    }

    public function getAuthUrl(string $redirectUri, string $clientId, string $state): string
    {
        return "https://auth.{$this->uri}/authorization?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}";
    }

    public function getToken(string $clientId, string $clientSecret, string $code, string $redirectUri)
    {
        return $this->request('POST', 'http://api.mercadolibre.com/oauth/token', [
            'grant_type'    => 'authorization_code',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'code'          => $code,
            'redirect_uri'  => $redirectUri,
        ]);
    }

    public function refreshToken(string $clientId, string $clientSecret, string $refreshToken)
    {
        return $this->request('POST', 'http://api.mercadolibre.com/oauth/token', [
            'form_params' => [
                'grant_type'    => 'refresh_token',
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
            ],
        ]);
    }
}


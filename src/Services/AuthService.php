<?php

namespace zdearo\Meli\Services;

use zdearo\Meli\Enums\MarketplaceEnum;
use zdearo\Meli\Http\MeliClient;

class AuthService
{
    private MeliClient $client;

    private string $clientId;

    private MarketplaceEnum $marketplace;

    public function __construct(MeliClient $client, string $clientId, MarketplaceEnum $marketplace)
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->marketplace = $marketplace;
    }

    public function getAuthUrl(string $redirectUri): string
    {
        $domain = $this->marketplace->domain();

        return "https://auth.{$domain}/authorization?response_type=code&client_id={$this->clientId}&redirect_uri={$redirectUri}";
    }
}

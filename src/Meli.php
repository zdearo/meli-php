<?php

namespace zdearo\Meli;

use zdearo\Meli\Enums\MarketplaceEnum;
use zdearo\Meli\Http\MeliClient;
use zdearo\Meli\Services\AuthService;

class Meli
{
    private MeliClient $client;

    private string $clientId;

    private MarketplaceEnum $marketplace;

    public function __construct(string $clientId, MarketplaceEnum $marketplace)
    {
        $this->client = new MeliClient;
        $this->clientId = $clientId;
        $this->marketplace = $marketplace;
    }

    public function auth(): AuthService
    {
        return new AuthService($this->client, $this->clientId, $this->marketplace);
    }
}

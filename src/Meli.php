<?php

namespace zdearo\Meli;

use zdearo\Meli\Http\MeliClient;
use zdearo\Meli\Services\AuthService;
use zdearo\Meli\Enums\MarketplaceEnum;

class Meli
{
    private MeliClient $client;
    private string $uri;

    public function __construct(string $region, string $apiToken = '')
    {
        $this->uri = MarketplaceEnum::{$region}->domain();
        $this->client = new MeliClient($apiToken, $this->uri);
    }

    public function auth(): AuthService
    {
        return new AuthService($this->client, $this->uri);
    }
}


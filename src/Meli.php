<?php

namespace zdearo\Meli;

use zdearo\Meli\Http\MeliClient;
use zdearo\Meli\Services\AuthService;
use zdearo\Meli\Enums\MarketplaceEnum;
use zdearo\Meli\Services\SearchItemService;

class Meli
{
    private MeliClient $client;
    private string $uri;

    public function __construct(string $region, string $apiToken = '')
    {
        $this->uri = MarketplaceEnum::{$region}->domain();

        if (!$this->uri) {
            throw new \Exception("Invalid region: $region");
        }

        $this->client = new MeliClient($apiToken, $this->uri);
    }

    public function auth(): AuthService
    {
        return new AuthService($this->client, $this->uri);
    }

    public function itemSearch(): SearchItemService
    {
        return new SearchItemService($this->client, $this->uri);
    }
}

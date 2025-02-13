<?php

namespace zdearo\Meli;

use zdearo\Meli\Http\MeliClient;
use zdearo\Meli\Services\AuthService;
use zdearo\Meli\Enums\MarketplaceEnum;
use zdearo\Meli\Services\SearchItemService;

class Meli
{
    private MeliClient $client;
    private MarketplaceEnum $region;

    public function __construct(string $region, string $apiToken = '')
    {
        $this->region = MarketplaceEnum::{$region};
        $this->client = new MeliClient($apiToken);
    }

    public function auth(): AuthService
    {
        return new AuthService($this->client, $this->region);
    }

    public function getRegion(): string
    {
        return $this->region->value;
    }

    public function searchItems(): SearchItemService
    {
        return new SearchItemService($this->client, $this->region);
    }
}

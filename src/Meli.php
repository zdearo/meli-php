<?php

namespace Zdearo\Meli;

use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Services\{AuthService, SearchItemService, ProductService, VisitsService};
use Zdearo\Meli\Enums\MarketplaceEnum;

class Meli
{
    private MeliClient $client;
    private MarketplaceEnum $region;

    public function __construct(string $region, string $apiToken = '')
    {
        $this->region = MarketplaceEnum::{$region};
        $this->client = new MeliClient($apiToken);
    }

    public function getRegion(): string
    {
        return $this->region->value;
    }

    public function auth(): AuthService
    {
        return new AuthService($this->region, $this->client);
    }

    public function searchItems(): SearchItemService
    {
        return new SearchItemService($this->region, $this->client);
    }

    public function products(): ProductService
    {
        return new ProductService($this->client);
    }

    public function visits(): VisitsService {
        return new VisitsService($this->client);
    }
}

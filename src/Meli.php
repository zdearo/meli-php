<?php

namespace Zdearo\Meli;

use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Services\{AuthService, SearchItemService, ProductService, VisitsService};
use Zdearo\Meli\Enums\MarketplaceEnum;

class Meli
{
    private MeliClient $client;
    private MarketplaceEnum $region;

    /**
     * Create a new Meli instance.
     *
     * @param string $region The marketplace region
     * @param string $apiToken The API token for authentication
     * @param float $timeout The timeout for API requests in seconds
     */
    public function __construct(string $region, string $apiToken = '', float $timeout = 10.0)
    {
        $this->region = constant(MarketplaceEnum::class . '::' . strtoupper($region));
        $this->client = new MeliClient($apiToken, $timeout);
    }

    /**
     * Get the current region.
     *
     * @return string
     */
    public function getRegion(): string
    {
        return $this->region->value;
    }

    /**
     * Get the authentication service.
     *
     * @return AuthService
     */
    public function auth(): AuthService
    {
        return new AuthService($this->region, $this->client);
    }

    /**
     * Get the search items service.
     *
     * @return SearchItemService
     */
    public function searchItems(): SearchItemService
    {
        return new SearchItemService($this->region, $this->client);
    }

    /**
     * Get the products service.
     *
     * @return ProductService
     */
    public function products(): ProductService
    {
        return new ProductService($this->client);
    }

    /**
     * Get the visits service.
     *
     * @return VisitsService
     */
    public function visits(): VisitsService 
    {
        return new VisitsService($this->client);
    }
}

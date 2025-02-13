<?php

namespace zdearo\Meli\Services;

use GuzzleHttp\Exception\RequestException;
use zdearo\Meli\Enums\MarketplaceEnum;
use zdearo\Meli\Http\MeliClient;

class SearchItemService
{
    private MeliClient $client;
    private string $uri;

    public function __construct(MeliClient $client, MarketplaceEnum $region)
    {
        $this->client = $client;
        $this->uri = "sites/{$region->value}/search";
    }

    public function byQuery(string $query)
    {
        try {
            $response = $this->client->getClient()->get("{$this->uri}", [
                'query' => ['q' => $query],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->client->handleRequestException($e)['message'];
        }
    }
}

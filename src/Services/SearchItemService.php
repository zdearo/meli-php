<?php

namespace zdearo\Meli\Services;

use GuzzleHttp\Exception\RequestException;
use zdearo\Meli\Http\MeliClient;

class SearchItemService
{
    private MeliClient $client;
    private string $uri;

    public function __construct(MeliClient $client, string $uri = 'sites/MLA/search')
    {
        $this->client = $client;
        $this->uri = $uri;
    }

    public function searchItems(string $query, string $token)
    {
        try {
            $response = $this->client->getClient()->get($this->uri, [
                'query' => ['q' => $query],
                'headers' => ['Authorization' => "Bearer $token"]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->client->handleRequestException($e)['message'];
        }
    }
}

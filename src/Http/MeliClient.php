<?php

namespace zdearo\Meli\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MeliClient
{
    private Client $client;
    private string $apiToken;

    public function __construct(string $apiToken)
    {
        $this->apiToken = $apiToken;
        $this->client = new Client([
            'base_uri' => "https://api.mercadolibre.com/",
            'headers'  => $this->getHeaders(),
            'timeout' => 10.0,
        ]);

    }

    private function getHeaders(): array
    {
        return [
            'accept'        => 'application/json',
            'authorization' => $this->apiToken ? "Bearer {$this->apiToken}" : '',
            'content-type'  => 'application/x-www-form-urlencoded',
        ];
    }

    public function handleRequestException(RequestException $e)
    {
        $response = $e->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 500;
        $body = $response ? json_decode($response->getBody()->getContents(), true) : [];

        return [
            'error' => true,
            'message' => $e->getMessage(),
            'status_code' => $statusCode,
            'response' => $body,
        ];
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}


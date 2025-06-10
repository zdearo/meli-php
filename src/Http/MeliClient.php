<?php

namespace Zdearo\Meli\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MeliClient
{
    private Client $client;
    private string $apiToken;
    private float $timeout;

    /**
     * Create a new MeliClient instance.
     *
     * @param string $apiToken The API token for authentication
     * @param float $timeout The timeout for API requests in seconds
     */
    public function __construct(string $apiToken, float $timeout = 10.0)
    {
        $this->apiToken = $apiToken;
        $this->timeout = $timeout;
        $this->client = new Client([
            'base_uri' => "https://api.mercadolibre.com/",
            'headers'  => $this->getHeaders(),
            'timeout' => $this->timeout,
        ]);
    }

    /**
     * Get the headers for API requests.
     *
     * @return array<string, string>
     */
    private function getHeaders(): array
    {
        return [
            'accept'        => 'application/json',
            'authorization' => $this->apiToken ? "Bearer {$this->apiToken}" : '',
            'content-type'  => 'application/json',
        ];
    }

    /**
     * Handle a request exception.
     *
     * @param RequestException $e The exception to handle
     * @return array<string, mixed> The formatted error response
     */
    public function handleRequestException(RequestException $e): array
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

    /**
     * Get the Guzzle client instance.
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }
}

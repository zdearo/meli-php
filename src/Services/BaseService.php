<?php

namespace Zdearo\Meli\Services;

use GuzzleHttp\Exception\RequestException;
use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Http\ResponseHandler;
use Zdearo\Meli\Exceptions\ApiException;

abstract class BaseService 
{
    /**
     * The HTTP client instance.
     *
     * @var MeliClient
     */
    protected MeliClient $client;

    /**
     * Create a new service instance.
     *
     * @param MeliClient $client The HTTP client
     */
    protected function __construct(MeliClient $client) 
    {
        $this->client = $client;
    }

    /**
     * Make an API request.
     *
     * @param string $method The HTTP method
     * @param string $uri The URI to request
     * @param array<string, mixed> $data The request data
     * @return array<string, mixed> The response data
     * @throws ApiException If the request fails
     */
    protected function request(string $method, string $uri, array $data = []): array 
    {
        try {
            $options = match ($method) {
                'GET' => ['query' => $data],
                default => ['json' => $data]
            };

            $response = $this->client->getClient()->request($method, $uri, $options);
            return ResponseHandler::handleResponse($response);
        } catch (RequestException $e) {
            ResponseHandler::handleException($e);
        }
    }    
}

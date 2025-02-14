<?php

namespace Zdearo\Meli\Services;

use GuzzleHttp\Exception\RequestException;
use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Http\ResponseHandler;

abstract class BaseService {
    protected MeliClient $client;

    protected function __construct(MeliClient $client) {
        $this->client = $client;
    }

    protected function request(string $method, string $uri, array $data = []) {
        try {
            $options = match ($method) {
                'GET' => ['query' => $data],
                default => ['json' => $data]
            };
    
            $response = $this->client->getClient()->request($method, $uri, $options);
            return ResponseHandler::handleResponse($response);
        } catch (RequestException $e) {
            return ResponseHandler::handleException($e);
        }
    }    
}

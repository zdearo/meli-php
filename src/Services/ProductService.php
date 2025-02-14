<?php

namespace zdearo\Meli\Services;

use GuzzleHttp\Exception\RequestException;
use zdearo\Meli\Http\MeliClient;

class ProductService
{
    private MeliClient $client;
    private string $uri = 'items';

    public function __construct(MeliClient $client)
    {
        $this->client = $client;
    }

    private function request(string $method, string $uri, array $data = [])
    {
        try {
            $options = !empty($data) ? ['json' => $data] : [];
            $response = $this->client->getClient()->request($method, $uri, $options);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->client->handleRequestException($e)['message'];
        }
    }

    public function createProduct(array $productData)
    {
        return $this->request('POST', $this->uri, $productData);
    }

    public function getProduct(string $itemId)
    {
        return $this->request('GET', "{$this->uri}/{$itemId}");
    }

    public function updateProduct(string $itemId, array $updateData)
    {
        return $this->request('PUT', "{$this->uri}/{$itemId}", $updateData);
    }

    public function changeStatus(string $itemId, string $status)
    {
        return $this->request('PUT', "{$this->uri}/{$itemId}", ['status' => $status]);
    }
}

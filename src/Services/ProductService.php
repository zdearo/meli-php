<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiClient;

/**
 * Service for managing products in the Mercado Libre API.
 */
class ProductService extends BaseService
{
    /**
     * The URI for product requests.
     *
     * @var string
     */
    private string $uri = 'items';

    /**
     * Create a new product service instance.
     *
     * @param ApiClient $client The HTTP client
     */
    public function __construct(ApiClient $client)
    {
        parent::__construct($client);
    }

    /**
     * Create a new product.
     *
     * @param array<string, mixed> $productData The product data
     * @return array<string, mixed> The created product
     * @throws ApiException If the request fails
     */
    public function create(array $productData): array
    {
        return $this->request('POST', $this->uri, $productData);
    }

    /**
     * Get a product by its ID.
     *
     * @param string $itemId The product ID
     * @return array<string, mixed> The product
     * @throws ApiException If the request fails
     */
    public function get(string $itemId): array
    {
        return $this->request('GET', "{$this->uri}/{$itemId}");
    }

    /**
     * Update a product.
     *
     * @param string $itemId The product ID
     * @param array<string, mixed> $updateData The update data
     * @return array<string, mixed> The updated product
     * @throws ApiException If the request fails
     */
    public function update(string $itemId, array $updateData): array
    {
        return $this->request('PUT', "{$this->uri}/{$itemId}", $updateData);
    }

    /**
     * Change the status of a product.
     *
     * @param string $itemId The product ID
     * @param string $status The new status
     * @return array<string, mixed> The updated product
     * @throws ApiException If the request fails
     */
    public function changeStatus(string $itemId, string $status): array
    {
        return $this->request('PUT', "{$this->uri}/{$itemId}", ['status' => $status]);
    }
}

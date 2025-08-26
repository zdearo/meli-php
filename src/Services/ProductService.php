<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for managing products in the Mercado Libre API.
 */
class ProductService
{
    /**
     * Create a new product.
     *
     * @param  array<string, mixed>  $productData  The product data
     * @return array<string, mixed> The created product
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function create(array $productData): array
    {
        return ApiRequest::post('items')
            ->withBody($productData)
            ->send()
            ->json();
    }

    /**
     * Get a product by its ID.
     *
     * @param  string  $itemId  The product ID
     * @return array<string, mixed> The product
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function get(string $itemId): array
    {
        return ApiRequest::get("items/{$itemId}")
            ->send()
            ->json();
    }

    /**
     * Update a product.
     *
     * @param  string  $itemId  The product ID
     * @param  array<string, mixed>  $updateData  The update data
     * @return array<string, mixed> The updated product
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function update(string $itemId, array $updateData): array
    {
        return ApiRequest::put("items/{$itemId}")
            ->withBody($updateData)
            ->send()
            ->json();
    }

    /**
     * Change the status of a product.
     *
     * @param  string  $itemId  The product ID
     * @param  string  $status  The new status
     * @return array<string, mixed> The updated product
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function changeStatus(string $itemId, string $status): array
    {
        return ApiRequest::put("items/{$itemId}")
            ->withBody(['status' => $status])
            ->send()
            ->json();
    }
}

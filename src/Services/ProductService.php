<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
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
     * @return Response The created product
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function create(array $productData): Response
    {
        return ApiRequest::post('items')
            ->withBody($productData)
            ->send();
    }

    /**
     * Get a product by its ID.
     *
     * @param  string  $itemId  The product ID
     * @return Response The product
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function get(string $itemId): Response
    {
        return ApiRequest::get("items/{$itemId}")
            ->send();
    }

    /**
     * Update a product.
     *
     * @param  string  $itemId  The product ID
     * @param  array<string, mixed>  $updateData  The update data
     * @return Response The updated product
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function update(string $itemId, array $updateData): Response
    {
        return ApiRequest::put("items/{$itemId}")
            ->withBody($updateData)
            ->send();
    }

    /**
     * Change the status of a product.
     *
     * @param  string  $itemId  The product ID
     * @param  string  $status  The new status
     * @return Response The updated product
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function changeStatus(string $itemId, string $status): Response
    {
        return ApiRequest::put("items/{$itemId}")
            ->withBody(['status' => $status])
            ->send();
    }
}

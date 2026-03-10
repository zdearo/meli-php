<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\DTO\Product;
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
     * @return Product The created product
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function create(array $productData): Product
    {
        $response = ApiRequest::post('items')
            ->withBody($productData)
            ->send();

        return Product::fromArray($response->json());
    }

    /**
     * Get a product by its ID.
     *
     * @param  string  $itemId  The product ID
     * @return Product The product
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function get(string $itemId): Product
    {
        $response = ApiRequest::get("items/{$itemId}")
            ->send();

        return Product::fromArray($response->json());
    }

    /**
     * Update a product.
     *
     * @param  string  $itemId  The product ID
     * @param  array<string, mixed>  $updateData  The update data
     * @return Product The updated product
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function update(string $itemId, array $updateData): Product
    {
        $response = ApiRequest::put("items/{$itemId}")
            ->withBody($updateData)
            ->send();

        return Product::fromArray($response->json());
    }

    /**
     * Change the status of a product.
     *
     * @param  string  $itemId  The product ID
     * @param  string  $status  The new status
     * @return Product The updated product
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function changeStatus(string $itemId, string $status): Product
    {
        $response = ApiRequest::put("items/{$itemId}")
            ->withBody(['status' => $status])
            ->send();

        return Product::fromArray($response->json());
    }
}

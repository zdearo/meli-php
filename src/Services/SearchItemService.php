<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for searching items in the Mercado Libre API.
 */
class SearchItemService
{
    /**
     * The site URI for search requests.
     */
    private string $siteUri;

    /**
     * Create a new search item service instance.
     *
     * @param  MarketplaceEnum  $region  The marketplace region
     */
    public function __construct(MarketplaceEnum $region)
    {
        $this->siteUri = "sites/{$region->value}/search";
    }

    /**
     * Search items by query.
     *
     * @param  string  $value  The search query
     * @param  int  $offset  The offset for pagination (optional)
     * @return array<string, mixed> The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function byQuery(string $value, int $offset = 0): array
    {
        return ApiRequest::get($this->siteUri)
            ->withQuery(['q' => $value, 'offset' => $offset])
            ->send()
            ->json();
    }

    /**
     * Search items by category.
     *
     * @param  string  $categoryId  The category ID
     * @return array<string, mixed> The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function byCategory(string $categoryId): array
    {
        return ApiRequest::get($this->siteUri)
            ->withQuery(['category' => $categoryId])
            ->send()
            ->json();
    }

    /**
     * Search items by seller nickname.
     *
     * @param  string  $nickname  The seller nickname
     * @return array<string, mixed> The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function byNickname(string $nickname): array
    {
        return ApiRequest::get($this->siteUri)
            ->withQuery(['nickname' => $nickname])
            ->send()
            ->json();
    }

    /**
     * Search items by seller ID.
     *
     * @param  int  $sellerId  The seller ID
     * @param  string|null  $categoryId  The category ID (optional)
     * @return array<string, mixed> The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function bySeller(int $sellerId, ?string $categoryId = null): array
    {
        $query = ['seller_id' => $sellerId];

        if ($categoryId) {
            $query['category'] = $categoryId;
        }

        return ApiRequest::get($this->siteUri)
            ->withQuery($query)
            ->send()
            ->json();
    }

    /**
     * Search items by user ID.
     *
     * @param  int  $userId  The user ID
     * @param  bool  $scan  Whether to use scan search type
     * @return array<string, mixed> The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function byUserItems(int $userId, bool $scan = false): array
    {
        $query = $scan ? ['search_type' => 'scan'] : [];

        return ApiRequest::get("users/{$userId}/items/search")
            ->withQuery($query)
            ->send()
            ->json();
    }

    /**
     * Get multiple items by their IDs.
     *
     * @param  array<int, string>  $itemIds  The item IDs
     * @param  array<int, string>  $attributes  The attributes to include (optional)
     * @return array<string, mixed> The items
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function multiGetItems(array $itemIds, array $attributes = []): array
    {
        $query = ['ids' => implode(',', $itemIds)];

        if (! empty($attributes)) {
            $query['attributes'] = implode(',', $attributes);
        }

        return ApiRequest::get('items')
            ->withQuery($query)
            ->send()
            ->json();
    }

    /**
     * Get multiple users by their IDs.
     *
     * @param  array<int, int>  $userIds  The user IDs
     * @return array<string, mixed> The users
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function multiGetUsers(array $userIds): array
    {
        return ApiRequest::get('users')
            ->withQuery(['ids' => implode(',', $userIds)])
            ->send()
            ->json();
    }
}

<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\Enums\MarketplaceEnum;
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
     * @return Response The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function byQuery(string $value, int $offset = 0): Response
    {
        return ApiRequest::get($this->siteUri)
            ->withQuery(['q' => $value, 'offset' => $offset])
            ->send();
    }

    /**
     * Search items by category.
     *
     * @param  string  $categoryId  The category ID
     * @return Response The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function byCategory(string $categoryId): Response
    {
        return ApiRequest::get($this->siteUri)
            ->withQuery(['category' => $categoryId])
            ->send();
    }

    /**
     * Search items by seller nickname.
     *
     * @param  string  $nickname  The seller nickname
     * @return Response The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function byNickname(string $nickname): Response
    {
        return ApiRequest::get($this->siteUri)
            ->withQuery(['nickname' => $nickname])
            ->send();
    }

    /**
     * Search items by seller ID.
     *
     * @param  int  $sellerId  The seller ID
     * @param  string|null  $categoryId  The category ID (optional)
     * @return Response The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function bySeller(int $sellerId, ?string $categoryId = null): Response
    {
        $query = ['seller_id' => $sellerId];

        if ($categoryId) {
            $query['category'] = $categoryId;
        }

        return ApiRequest::get($this->siteUri)
            ->withQuery($query)
            ->send();
    }

    /**
     * Search items by user ID.
     *
     * @param  int  $userId  The user ID
     * @param  bool  $scan  Whether to use scan search type
     * @return Response The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function byUserItems(int $userId, bool $scan = false): Response
    {
        $query = $scan ? ['search_type' => 'scan'] : [];

        return ApiRequest::get("users/{$userId}/items/search")
            ->withQuery($query)
            ->send();
    }

    /**
     * Get multiple items by their IDs.
     *
     * @param  array<int, string>  $itemIds  The item IDs
     * @param  array<int, string>  $attributes  The attributes to include (optional)
     * @return Response The items
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function multiGetItems(array $itemIds, array $attributes = []): Response
    {
        $query = ['ids' => implode(',', $itemIds)];

        if (! empty($attributes)) {
            $query['attributes'] = implode(',', $attributes);
        }

        return ApiRequest::get('items')
            ->withQuery($query)
            ->send();
    }

    /**
     * Get multiple users by their IDs.
     *
     * @param  array<int, int>  $userIds  The user IDs
     * @return Response The users
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function multiGetUsers(array $userIds): Response
    {
        return ApiRequest::get('users')
            ->withQuery(['ids' => implode(',', $userIds)])
            ->send();
    }
}

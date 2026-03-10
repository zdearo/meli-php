<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\DTO\SearchResult;
use Zdearo\Meli\DTO\Product;
use Zdearo\Meli\DTO\User;
use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Support\ApiRequest;
use Zdearo\Meli\Support\LazyPaginator;

/**
 * Service for searching items in the Mercado Libre API.
 */
class SearchItemService
{
    private string $siteUri;

    public function __construct(MarketplaceEnum $region)
    {
        $this->siteUri = "sites/{$region->value}/search";
    }

    /**
     * Search items by query.
     *
     * @param  string  $value  The search query
     * @param  int  $offset  The offset for pagination
     * @return SearchResult The search results
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function byQuery(string $value, int $offset = 0): SearchResult
    {
        $data = ApiRequest::get($this->siteUri)
            ->withQuery(['q' => $value, 'offset' => $offset])
            ->send()
            ->json();

        return new SearchResult(
            results: $data['results'] ?? [],
            total: $data['paging']['total'] ?? 0,
            offset: $data['paging']['offset'] ?? 0,
            limit: $data['paging']['limit'] ?? 50,
        );
    }

    /**
     * Lazily iterate over all items matching a query.
     *
     * @param  string  $value  The search query
     * @param  int  $limit  Results per page
     * @return LazyPaginator Paginator yielding search result items
     */
    public function byQueryAll(string $value, int $limit = 50): LazyPaginator
    {
        return new LazyPaginator(
            fetcher: function (int $offset) use ($value) {
                return $this->byQuery($value, $offset);
            },
            limit: $limit,
        );
    }

    /**
     * Search items by category.
     *
     * @param  string  $categoryId  The category ID
     * @return SearchResult The search results
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function byCategory(string $categoryId): SearchResult
    {
        $data = ApiRequest::get($this->siteUri)
            ->withQuery(['category' => $categoryId])
            ->send()
            ->json();

        return new SearchResult(
            results: $data['results'] ?? [],
            total: $data['paging']['total'] ?? 0,
            offset: $data['paging']['offset'] ?? 0,
            limit: $data['paging']['limit'] ?? 50,
        );
    }

    /**
     * Search items by seller nickname.
     *
     * @param  string  $nickname  The seller nickname
     * @return SearchResult The search results
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function byNickname(string $nickname): SearchResult
    {
        $data = ApiRequest::get($this->siteUri)
            ->withQuery(['nickname' => $nickname])
            ->send()
            ->json();

        return new SearchResult(
            results: $data['results'] ?? [],
            total: $data['paging']['total'] ?? 0,
            offset: $data['paging']['offset'] ?? 0,
            limit: $data['paging']['limit'] ?? 50,
        );
    }

    /**
     * Search items by seller ID.
     *
     * @param  int  $sellerId  The seller ID
     * @param  string|null  $categoryId  The category ID (optional)
     * @return SearchResult The search results
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function bySeller(int $sellerId, ?string $categoryId = null): SearchResult
    {
        $query = ['seller_id' => $sellerId];

        if ($categoryId) {
            $query['category'] = $categoryId;
        }

        $data = ApiRequest::get($this->siteUri)
            ->withQuery($query)
            ->send()
            ->json();

        return new SearchResult(
            results: $data['results'] ?? [],
            total: $data['paging']['total'] ?? 0,
            offset: $data['paging']['offset'] ?? 0,
            limit: $data['paging']['limit'] ?? 50,
        );
    }

    /**
     * Search items by user ID.
     *
     * @param  int  $userId  The user ID
     * @param  bool  $scan  Whether to use scan search type
     * @return Response The search results
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
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
     * @return array<Product> The items as Product DTOs
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function multiGetItems(array $itemIds, array $attributes = []): array
    {
        $query = ['ids' => implode(',', $itemIds)];

        if (! empty($attributes)) {
            $query['attributes'] = implode(',', $attributes);
        }

        $data = ApiRequest::get('items')
            ->withQuery($query)
            ->send()
            ->json();

        return array_map(
            fn (array $item) => Product::fromArray($item['body'] ?? $item),
            $data
        );
    }

    /**
     * Get multiple users by their IDs.
     *
     * @param  array<int, int>  $userIds  The user IDs
     * @return array<User> The users as User DTOs
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function multiGetUsers(array $userIds): array
    {
        $data = ApiRequest::get('users')
            ->withQuery(['ids' => implode(',', $userIds)])
            ->send()
            ->json();

        return array_map(
            fn (array $user) => User::fromArray($user['body'] ?? $user),
            $data
        );
    }
}

<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiClient;

/**
 * Service for searching items in the Mercado Libre API.
 */
class SearchItemService extends BaseService
{
    /**
     * The site URI for search requests.
     *
     * @var string
     */
    private string $siteUri;

    /**
     * Create a new search item service instance.
     *
     * @param MarketplaceEnum $region The marketplace region
     * @param ApiClient $client The HTTP client
     */
    public function __construct(MarketplaceEnum $region, ApiClient $client)
    {
        parent::__construct($client);
        $this->siteUri = "sites/{$region->value}/search";
    }

    /**
     * Search items by query.
     *
     * @param string $value The search query
     * @param int $offset The offset for pagination (optional)
     * @return array<string, mixed> The search results
     * @throws ApiException If the request fails
     */
    public function byQuery(string $value, int $offset = 0): array
    {
        return $this->request('GET', $this->siteUri, ['q' => $value, 'offset' => $offset]);
    }

    /**
     * Search items by category.
     *
     * @param string $categoryId The category ID
     * @return array<string, mixed> The search results
     * @throws ApiException If the request fails
     */
    public function byCategory(string $categoryId): array
    {
        return $this->request('GET', $this->siteUri, ['category' => $categoryId]);
    }

    /**
     * Search items by seller nickname.
     *
     * @param string $nickname The seller nickname
     * @return array<string, mixed> The search results
     * @throws ApiException If the request fails
     */
    public function byNickname(string $nickname): array
    {
        return $this->request('GET', $this->siteUri, ['nickname' => $nickname]);
    }

    /**
     * Search items by seller ID.
     *
     * @param int $sellerId The seller ID
     * @param string|null $categoryId The category ID (optional)
     * @return array<string, mixed> The search results
     * @throws ApiException If the request fails
     */
    public function bySeller(int $sellerId, ?string $categoryId = null): array
    {
        $value = ['seller_id' => $sellerId];
        if ($categoryId) {
            $value['category'] = $categoryId;
        }
        return $this->request('GET', $this->siteUri, $value);
    }

    /**
     * Search items by user ID.
     *
     * @param int $userId The user ID
     * @param bool $scan Whether to use scan search type
     * @return array<string, mixed> The search results
     * @throws ApiException If the request fails
     */
    public function byUserItems(int $userId, bool $scan = false): array
    {
        $value = $scan ? ['search_type' => 'scan'] : [];
        return $this->request('GET', "users/{$userId}/items/search", $value);
    }

    /**
     * Get multiple items by their IDs.
     *
     * @param array<int, string> $itemIds The item IDs
     * @param array<int, string> $attributes The attributes to include (optional)
     * @return array<string, mixed> The items
     * @throws ApiException If the request fails
     */
    public function multiGetItems(array $itemIds, array $attributes = []): array
    {
        $value = ['ids' => implode(',', $itemIds)];
        if (!empty($attributes)) {
            $value['attributes'] = implode(',', $attributes);
        }
        return $this->request('GET', 'items', $value);
    }

    /**
     * Get multiple users by their IDs.
     *
     * @param array<int, int> $userIds The user IDs
     * @return array<string, mixed> The users
     * @throws ApiException If the request fails
     */
    public function multiGetUsers(array $userIds): array
    {
        return $this->request('GET', 'users', ['ids' => implode(',', $userIds)]);
    }
}

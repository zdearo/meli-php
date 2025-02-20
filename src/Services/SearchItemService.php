<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Services\BaseService;
use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Http\MeliClient;

class SearchItemService extends BaseService
{
    private string $siteUri;

    public function __construct(MarketplaceEnum $region, MeliClient $client)
    {
        parent::__construct($client);
        $this->siteUri = "sites/{$region->value}/search";
    }

    public function byQuery(string $value, int $offset = 0)
    {
        return $this->request('GET', $this->siteUri, ['q' => $value, 'offset' => $offset]);
    }

    public function byCategory(string $categoryId)
    {
        return $this->request('GET', $this->siteUri, ['category' => $categoryId]);
    }

    public function byNickname(string $nickname)
    {
        return $this->request('GET', $this->siteUri, ['nickname' => $nickname]);
    }

    public function bySeller(int $sellerId, string $categoryId = null)
    {
        $value = ['seller_id' => $sellerId];
        if ($categoryId) {
            $value['category'] = $categoryId;
        }
        return $this->request('GET', $this->siteUri, $value);
    }

    public function byUserItems(int $userId, bool $scan = false)
    {
        $value = $scan ? ['search_type' => 'scan'] : [];
        return $this->request('GET', "users/{$userId}/items/search", $value);
    }

    public function multiGetItems(array $itemIds, array $attributes = [])
    {
        $value = ['ids' => implode(',', $itemIds)];
        if (!empty($attributes)) {
            $value['attributes'] = implode(',', $attributes);
        }
        return $this->request('GET', 'items', $value);
    }

    public function multiGetUsers(array $userIds)
    {
        return $this->request('GET', 'users', ['ids' => implode(',', $userIds)]);
    }
}

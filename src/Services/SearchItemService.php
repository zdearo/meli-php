<?php

namespace zdearo\Meli\Services;

use GuzzleHttp\Exception\RequestException;
use zdearo\Meli\Enums\MarketplaceEnum;
use zdearo\Meli\Http\MeliClient;

class SearchItemService
{
    private MeliClient $client;
    private string $siteUri;

    public function __construct(MeliClient $client, MarketplaceEnum $region)
    {
        $this->client = $client;
        $this->siteUri = "sites/{$region->value}/search";
    }

    private function fetch(string $uri, array $value = [])
    {
        try {
            $response = $this->client->getClient()->get($uri, ['query' => $value]);
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->client->handleRequestException($e)['message'];
        }
    }

    public function byQuery(string $value)
    {
        return $this->fetch($this->siteUri, ['q' => $value]);
    }

    public function byCategory(string $categoryId)
    {
        return $this->fetch($this->siteUri, ['category' => $categoryId]);
    }

    public function byNickname(string $nickname)
    {
        return $this->fetch($this->siteUri, ['nickname' => $nickname]);
    }

    public function bySeller(int $sellerId, ?string $categoryId = null)
    {
        $value = ['seller_id' => $sellerId];
        if ($categoryId) {
            $value['category'] = $categoryId;
        }
        return $this->fetch($this->siteUri, $value);
    }

    public function byUserItems(int $userId, bool $scan = false)
    {
        $value = $scan ? ['search_type' => 'scan'] : [];
        return $this->fetch("users/{$userId}/items/search", $value);
    }

    public function multiGetItems(array $itemIds, array $attributes = [])
    {
        $value = ['ids' => implode(',', $itemIds)];
        if (!empty($attributes)) {
            $value['attributes'] = implode(',', $attributes);
        }
        return $this->fetch('items', $value);
    }

    public function multiGetUsers(array $userIds)
    {
        return $this->fetch('users', ['ids' => implode(',', $userIds)]);
    }
}

<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Services\BaseService;
use Zdearo\Meli\Http\MeliClient;

class ProductService extends BaseService
{
    private string $uri = 'items';

    public function __construct(MeliClient $client)
    {
        parent::__construct($client);
    }

    public function create(array $productData)
    {
        return $this->request('POST', $this->uri, $productData);
    }

    public function get(string $itemId)
    {
        return $this->request('GET', "{$this->uri}/{$itemId}");
    }

    public function update(string $itemId, array $updateData)
    {
        return $this->request('PUT', "{$this->uri}/{$itemId}", $updateData);
    }

    public function changeStatus(string $itemId, string $status)
    {
        return $this->request('PUT', "{$this->uri}/{$itemId}", ['status' => $status]);
    }
}

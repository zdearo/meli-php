<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Services\BaseService;

class VisitsService extends BaseService
{
    public function __construct(MeliClient $client)
    {
        parent::__construct($client);
    }

    public function totalByUser(int $userId, string $dateFrom, string $dateTo)
    {
        $uri = "users/{$userId}/items_visits";
        return $this->request('GET', $uri, [
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);
    }

    public function totalByItem(string $itemId)
    {
        $uri = "visits/items";
        return $this->request('GET', $uri, ['ids' => $itemId]);
    }

    public function totalByItemsDateRange(array $itemIds, string $dateFrom, string $dateTo)
    {
        $uri = "items/visits";
        return $this->request('GET', $uri, [
            'ids'       => implode(',', $itemIds),
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);
    }

    public function visitsByUserTimeWindow(int $userId, int $last, string $unit, ?string $ending = null)
    {
        $uri = "users/{$userId}/items_visits/time_window";
        $params = [
            'last' => $last,
            'unit' => $unit,
        ];

        if ($ending) {
            $params['ending'] = $ending;
        }

        return $this->request('GET', $uri, $params);
    }

    public function visitsByItemTimeWindow(string $itemId, int $last, string $unit, ?string $ending = null)
    {
        $uri = "items/{$itemId}/visits/time_window";
        $params = [
            'last' => $last,
            'unit' => $unit,
        ];

        if ($ending) {
            $params['ending'] = $ending;
        }

        return $this->request('GET', $uri, $params);
    }
}

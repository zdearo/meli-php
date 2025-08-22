<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiClient;

/**
 * Service for retrieving visit statistics from the Mercado Libre API.
 */
class VisitsService extends BaseService
{
    /**
     * Create a new visits service instance.
     *
     * @param ApiClient $client The HTTP client
     */
    public function __construct(ApiClient $client)
    {
        parent::__construct($client);
    }

    /**
     * Get total visits for a user's items within a date range.
     *
     * @param int $userId The user ID
     * @param string $dateFrom The start date (format: YYYY-MM-DD)
     * @param string $dateTo The end date (format: YYYY-MM-DD)
     * @return array<string, mixed> The visit statistics
     * @throws ApiException If the request fails
     */
    public function totalByUser(int $userId, string $dateFrom, string $dateTo): array
    {
        $uri = "users/{$userId}/items_visits";
        return $this->request('GET', $uri, [
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);
    }

    /**
     * Get total visits for an item.
     *
     * @param string $itemId The item ID
     * @return array<string, mixed> The visit statistics
     * @throws ApiException If the request fails
     */
    public function totalByItem(string $itemId): array
    {
        $uri = "visits/items";
        return $this->request('GET', $uri, ['ids' => $itemId]);
    }

    /**
     * Get total visits for multiple items within a date range.
     *
     * @param array<int, string> $itemIds The item IDs
     * @param string $dateFrom The start date (format: YYYY-MM-DD)
     * @param string $dateTo The end date (format: YYYY-MM-DD)
     * @return array<string, mixed> The visit statistics
     * @throws ApiException If the request fails
     */
    public function totalByItemsDateRange(array $itemIds, string $dateFrom, string $dateTo): array
    {
        $uri = "items/visits";
        return $this->request('GET', $uri, [
            'ids'       => implode(',', $itemIds),
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);
    }

    /**
     * Get visits for a user's items within a time window.
     *
     * @param int $userId The user ID
     * @param int $last The number of time units
     * @param string $unit The time unit (day, week, month)
     * @param string|null $ending The end date (format: YYYY-MM-DD)
     * @return array<string, mixed> The visit statistics
     * @throws ApiException If the request fails
     */
    public function visitsByUserTimeWindow(int $userId, int $last, string $unit, ?string $ending = null): array
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

    /**
     * Get visits for an item within a time window.
     *
     * @param string $itemId The item ID
     * @param int $last The number of time units
     * @param string $unit The time unit (day, week, month)
     * @param string|null $ending The end date (format: YYYY-MM-DD)
     * @return array<string, mixed> The visit statistics
     * @throws ApiException If the request fails
     */
    public function visitsByItemTimeWindow(string $itemId, int $last, string $unit, ?string $ending = null): array
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

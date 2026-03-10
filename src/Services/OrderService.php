<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\DTO\Order;
use Zdearo\Meli\DTO\SearchResult;
use Zdearo\Meli\Support\ApiRequest;
use Zdearo\Meli\Support\LazyPaginator;

/**
 * Service for managing orders in the Mercado Libre API.
 */
class OrderService
{
    /**
     * Get order details by ID.
     *
     * @param  int  $orderId  The order ID
     * @return Order The order details
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function get(int $orderId): Order
    {
        $response = ApiRequest::get("orders/{$orderId}")
            ->send();

        return Order::fromArray($response->json());
    }

    /**
     * Search orders with filters.
     *
     * @param  array<string, mixed>  $filters  The search filters
     * @return SearchResult The search results containing Order DTOs
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function search(array $filters = []): SearchResult
    {
        $request = ApiRequest::get('orders/search');

        if (! empty($filters)) {
            $request->withQuery($filters);
        }

        $data = $request->send()->json();

        return new SearchResult(
            results: array_map(Order::fromArray(...), $data['results'] ?? []),
            total: $data['paging']['total'] ?? 0,
            offset: $data['paging']['offset'] ?? 0,
            limit: $data['paging']['limit'] ?? 50,
        );
    }

    /**
     * Lazily iterate over all orders matching the filters.
     *
     * @param  array<string, mixed>  $filters  Search filters
     * @param  int  $limit  Results per page
     * @return LazyPaginator Paginator yielding Order DTOs
     */
    public function searchAll(array $filters = [], int $limit = 50): LazyPaginator
    {
        return new LazyPaginator(
            fetcher: function (int $offset, int $limit) use ($filters) {
                $filters['limit'] = $limit;
                $filters['offset'] = $offset;

                return $this->search($filters);
            },
            limit: $limit,
        );
    }

    /**
     * Get orders by seller ID.
     *
     * @param  int  $sellerId  The seller ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return SearchResult The seller orders
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getBySeller(int $sellerId, array $filters = []): SearchResult
    {
        $filters['seller'] = $sellerId;

        return $this->search($filters);
    }

    /**
     * Get orders by buyer ID.
     *
     * @param  int  $buyerId  The buyer ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return SearchResult The buyer orders
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getByBuyer(int $buyerId, array $filters = []): SearchResult
    {
        $filters['buyer'] = $buyerId;

        return $this->search($filters);
    }

    /**
     * Get orders by status.
     *
     * @param  string  $status  The order status
     * @param  array<string, mixed>  $filters  Additional filters
     * @return SearchResult The filtered orders
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getByStatus(string $status, array $filters = []): SearchResult
    {
        $filters['order.status'] = $status;

        return $this->search($filters);
    }

    /**
     * Get orders by date range.
     *
     * @param  string  $dateFrom  The start date (ISO format)
     * @param  string  $dateTo  The end date (ISO format)
     * @param  string  $dateField  The date field to filter by (created, updated, closed)
     * @param  array<string, mixed>  $filters  Additional filters
     * @return SearchResult The filtered orders
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): SearchResult
    {
        $filters["order.date_{$dateField}.from"] = $dateFrom;
        $filters["order.date_{$dateField}.to"] = $dateTo;

        return $this->search($filters);
    }

    /**
     * Get orders with specific tags.
     *
     * @param  string|array<string>  $tags  The tags to filter by
     * @param  array<string, mixed>  $filters  Additional filters
     * @return SearchResult The filtered orders
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getByTags($tags, array $filters = []): SearchResult
    {
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }

        $filters['tags'] = $tags;

        return $this->search($filters);
    }

    /**
     * Get orders excluding specific tags.
     *
     * @param  string|array<string>  $tags  The tags to exclude
     * @param  array<string, mixed>  $filters  Additional filters
     * @return SearchResult The filtered orders
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getExcludingTags($tags, array $filters = []): SearchResult
    {
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }

        $filters['tags.not'] = $tags;

        return $this->search($filters);
    }

    /**
     * Get Mercado Shops orders.
     */
    public function getMercadoShopsOrders(array $filters = []): SearchResult
    {
        return $this->getByTags('mshops', $filters);
    }

    /**
     * Get product information for an order.
     *
     * @param  int  $orderId  The order ID
     * @return Response The product information
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getProductInfo(int $orderId): Response
    {
        return ApiRequest::get("orders/{$orderId}/product")
            ->send();
    }

    /**
     * Get discounts applied to an order.
     *
     * @param  int  $orderId  The order ID
     * @return Response The order discounts
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getDiscounts(int $orderId): Response
    {
        return ApiRequest::get("orders/{$orderId}/discounts")
            ->send();
    }

    /**
     * Search orders with pagination and sorting.
     *
     * @param  array<string, mixed>  $filters  Search filters
     * @param  int  $limit  Number of results per page
     * @param  int  $offset  Results offset
     * @param  string  $sort  Sort order (date_desc, date_asc)
     * @return SearchResult The paginated results
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function searchPaginated(array $filters = [], int $limit = 50, int $offset = 0, string $sort = 'date_asc'): SearchResult
    {
        $filters['limit'] = $limit;
        $filters['offset'] = $offset;
        $filters['sort'] = $sort;

        return $this->search($filters);
    }

    /**
     * Get fraud risk orders.
     */
    public function getFraudRiskOrders(array $filters = []): SearchResult
    {
        return $this->getByTags('fraud_risk_detected', $filters);
    }

    /**
     * Get paid orders.
     */
    public function getPaidOrders(array $filters = []): SearchResult
    {
        return $this->getByStatus('paid', $filters);
    }

    /**
     * Get delivered orders.
     */
    public function getDeliveredOrders(array $filters = []): SearchResult
    {
        return $this->getByTags('delivered', $filters);
    }

    /**
     * Get cancelled orders.
     */
    public function getCancelledOrders(array $filters = []): SearchResult
    {
        return $this->getByStatus('cancelled', $filters);
    }
}

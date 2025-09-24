<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for managing orders in the Mercado Libre API.
 */
class OrderService
{
    /**
     * Get order details by ID.
     *
     * @param  int  $orderId  The order ID
     * @return Response The order details
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function get(int $orderId): Response
    {
        return ApiRequest::get("orders/{$orderId}")
            ->send();
    }

    /**
     * Search orders with filters.
     *
     * @param  array<string, mixed>  $filters  The search filters
     * @return Response The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function search(array $filters = []): Response
    {
        $request = ApiRequest::get('orders/search');

        if (! empty($filters)) {
            $request->withQuery($filters);
        }

        return $request->send();
    }

    /**
     * Get orders by seller ID.
     *
     * @param  int  $sellerId  The seller ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The seller orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getBySeller(int $sellerId, array $filters = []): Response
    {
        $filters['seller'] = $sellerId;

        return $this->search($filters);
    }

    /**
     * Get orders by buyer ID.
     *
     * @param  int  $buyerId  The buyer ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The buyer orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByBuyer(int $buyerId, array $filters = []): Response
    {
        $filters['buyer'] = $buyerId;

        return $this->search($filters);
    }

    /**
     * Get orders by status.
     *
     * @param  string  $status  The order status
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The filtered orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByStatus(string $status, array $filters = []): Response
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
     * @return Response The filtered orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): Response
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
     * @return Response The filtered orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByTags($tags, array $filters = []): Response
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
     * @return Response The filtered orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getExcludingTags($tags, array $filters = []): Response
    {
        if (is_array($tags)) {
            $tags = implode(',', $tags);
        }

        $filters['tags.not'] = $tags;

        return $this->search($filters);
    }

    /**
     * Get Mercado Shops orders.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The Mercado Shops orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getMercadoShopsOrders(array $filters = []): Response
    {
        return $this->getByTags('mshops', $filters);
    }

    /**
     * Get product information for an order.
     *
     * @param  int  $orderId  The order ID
     * @return Response The product information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
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
     * @throws IlluminateHttpClientRequestException If the request fails
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
     * @return Response The paginated results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function searchPaginated(array $filters = [], int $limit = 50, int $offset = 0, string $sort = 'date_asc'): Response
    {
        $filters['limit'] = $limit;
        $filters['offset'] = $offset;
        $filters['sort'] = $sort;

        return $this->search($filters);
    }

    /**
     * Get fraud risk orders.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The fraud risk orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getFraudRiskOrders(array $filters = []): Response
    {
        return $this->getByTags('fraud_risk_detected', $filters);
    }

    /**
     * Get paid orders.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The paid orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getPaidOrders(array $filters = []): Response
    {
        return $this->getByStatus('paid', $filters);
    }

    /**
     * Get delivered orders.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The delivered orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getDeliveredOrders(array $filters = []): Response
    {
        return $this->getByTags('delivered', $filters);
    }

    /**
     * Get cancelled orders.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The cancelled orders
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getCancelledOrders(array $filters = []): Response
    {
        return $this->getByStatus('cancelled', $filters);
    }
}

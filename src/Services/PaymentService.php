<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\DTO\Payment;
use Zdearo\Meli\DTO\SearchResult;
use Zdearo\Meli\Support\ApiRequest;
use Zdearo\Meli\Support\LazyPaginator;

/**
 * Service for managing payments in the Mercado Libre API.
 */
class PaymentService
{
    /**
     * Get payment details by ID (collection).
     *
     * @param  int  $paymentId  The payment ID
     * @return Payment The payment details
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function get(int $paymentId): Payment
    {
        $data = ApiRequest::get("collections/{$paymentId}")
            ->send()
            ->json();

        return Payment::fromArray($data);
    }

    /**
     * Search payments with filters.
     *
     * @param  array<string, mixed>  $filters  The search filters
     * @return SearchResult The search results containing Payment DTOs
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function search(array $filters = []): SearchResult
    {
        $data = ApiRequest::get('payments/search')
            ->withQuery($filters)
            ->send()
            ->json();

        return new SearchResult(
            results: array_map(Payment::fromArray(...), $data['results'] ?? []),
            total: $data['paging']['total'] ?? 0,
            offset: $data['paging']['offset'] ?? 0,
            limit: $data['paging']['limit'] ?? 50,
        );
    }

    /**
     * Lazily iterate over all payments matching the filters.
     *
     * @param  array<string, mixed>  $filters  Search filters
     * @param  int  $limit  Results per page
     * @return LazyPaginator Paginator yielding Payment DTOs
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
     * Get payments by order ID.
     */
    public function getByOrder(int $orderId): SearchResult
    {
        return $this->search(['order_id' => $orderId]);
    }

    /**
     * Get payments by external reference.
     */
    public function getByExternalReference(string $externalReference): SearchResult
    {
        return $this->search(['external_reference' => $externalReference]);
    }

    /**
     * Get payments by status.
     *
     * @param  string  $status  The payment status (approved, rejected, pending, etc.)
     */
    public function getByStatus(string $status, array $filters = []): SearchResult
    {
        $filters['status'] = $status;

        return $this->search($filters);
    }

    public function getApprovedPayments(array $filters = []): SearchResult
    {
        return $this->getByStatus('approved', $filters);
    }

    public function getPendingPayments(array $filters = []): SearchResult
    {
        return $this->getByStatus('pending', $filters);
    }

    public function getRejectedPayments(array $filters = []): SearchResult
    {
        return $this->getByStatus('rejected', $filters);
    }

    public function getCancelledPayments(array $filters = []): SearchResult
    {
        return $this->getByStatus('cancelled', $filters);
    }

    public function getRefundedPayments(array $filters = []): SearchResult
    {
        return $this->getByStatus('refunded', $filters);
    }

    /**
     * Get payments by date range.
     *
     * @param  string  $dateFrom  The start date (ISO format)
     * @param  string  $dateTo  The end date (ISO format)
     * @param  string  $dateField  The date field to filter by (created, approved, etc.)
     */
    public function getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): SearchResult
    {
        $filters["date_{$dateField}.from"] = $dateFrom;
        $filters["date_{$dateField}.to"] = $dateTo;

        return $this->search($filters);
    }

    /**
     * Get payments by payment method.
     *
     * @param  string  $paymentMethodId  The payment method ID (visa, master, account_money, etc.)
     */
    public function getByPaymentMethod(string $paymentMethodId, array $filters = []): SearchResult
    {
        $filters['payment_method_id'] = $paymentMethodId;

        return $this->search($filters);
    }

    /**
     * Get payments by collector (seller).
     */
    public function getByCollector(int $collectorId, array $filters = []): SearchResult
    {
        $filters['collector_id'] = $collectorId;

        return $this->search($filters);
    }

    /**
     * Get payments by payer.
     */
    public function getByPayer(int $payerId, array $filters = []): SearchResult
    {
        $filters['payer_id'] = $payerId;

        return $this->search($filters);
    }

    /**
     * Search payments with pagination.
     */
    public function searchPaginated(array $filters = [], int $limit = 50, int $offset = 0): SearchResult
    {
        $filters['limit'] = $limit;
        $filters['offset'] = $offset;

        return $this->search($filters);
    }

    public function getAccountMoneyPayments(array $filters = []): SearchResult
    {
        return $this->getByPaymentMethod('account_money', $filters);
    }

    public function getCreditCardPayments(array $filters = []): SearchResult
    {
        $filters['payment_type'] = 'credit_card';

        return $this->search($filters);
    }

    public function getDebitCardPayments(array $filters = []): SearchResult
    {
        $filters['payment_type'] = 'debit_card';

        return $this->search($filters);
    }

    public function getBankTransferPayments(array $filters = []): SearchResult
    {
        $filters['payment_type'] = 'bank_transfer';

        return $this->search($filters);
    }

    public function getTicketPayments(array $filters = []): SearchResult
    {
        $filters['payment_type'] = 'ticket';

        return $this->search($filters);
    }

    public function getRefundAccountMoneyPayments(array $filters = []): SearchResult
    {
        $filters['tags'] = 'refund_account_money';

        return $this->search($filters);
    }
}

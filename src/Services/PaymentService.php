<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for managing payments in the Mercado Libre API.
 */
class PaymentService
{
    /**
     * Get payment details by ID (collection).
     *
     * @param  int  $paymentId  The payment ID
     * @return array<string, mixed> The payment details
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function get(int $paymentId): array
    {
        return ApiRequest::get("collections/{$paymentId}")
            ->send()
            ->json();
    }

    /**
     * Search payments with filters.
     *
     * @param  array<string, mixed>  $filters  The search filters
     * @return array<string, mixed> The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function search(array $filters = []): array
    {
        return ApiRequest::get('payments/search')
            ->withQuery($filters)
            ->send()
            ->json();
    }

    /**
     * Get payments by order ID.
     *
     * @param  int  $orderId  The order ID
     * @return array<string, mixed> The order payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByOrder(int $orderId): array
    {
        return $this->search(['order_id' => $orderId]);
    }

    /**
     * Get payments by external reference.
     *
     * @param  string  $externalReference  The external reference
     * @return array<string, mixed> The payments with that reference
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByExternalReference(string $externalReference): array
    {
        return $this->search(['external_reference' => $externalReference]);
    }

    /**
     * Get payments by status.
     *
     * @param  string  $status  The payment status (approved, rejected, pending, etc.)
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The filtered payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByStatus(string $status, array $filters = []): array
    {
        $filters['status'] = $status;
        return $this->search($filters);
    }

    /**
     * Get approved payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The approved payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getApprovedPayments(array $filters = []): array
    {
        return $this->getByStatus('approved', $filters);
    }

    /**
     * Get pending payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The pending payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getPendingPayments(array $filters = []): array
    {
        return $this->getByStatus('pending', $filters);
    }

    /**
     * Get rejected payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The rejected payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getRejectedPayments(array $filters = []): array
    {
        return $this->getByStatus('rejected', $filters);
    }

    /**
     * Get cancelled payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The cancelled payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getCancelledPayments(array $filters = []): array
    {
        return $this->getByStatus('cancelled', $filters);
    }

    /**
     * Get refunded payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The refunded payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getRefundedPayments(array $filters = []): array
    {
        return $this->getByStatus('refunded', $filters);
    }

    /**
     * Get payments by date range.
     *
     * @param  string  $dateFrom  The start date (ISO format)
     * @param  string  $dateTo  The end date (ISO format)
     * @param  string  $dateField  The date field to filter by (created, approved, etc.)
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The filtered payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): array
    {
        $filters["date_{$dateField}.from"] = $dateFrom;
        $filters["date_{$dateField}.to"] = $dateTo;
        return $this->search($filters);
    }

    /**
     * Get payments by payment method.
     *
     * @param  string  $paymentMethodId  The payment method ID (visa, master, account_money, etc.)
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The filtered payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByPaymentMethod(string $paymentMethodId, array $filters = []): array
    {
        $filters['payment_method_id'] = $paymentMethodId;
        return $this->search($filters);
    }

    /**
     * Get payments by collector (seller).
     *
     * @param  int  $collectorId  The collector/seller ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The collector payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByCollector(int $collectorId, array $filters = []): array
    {
        $filters['collector_id'] = $collectorId;
        return $this->search($filters);
    }

    /**
     * Get payments by payer.
     *
     * @param  int  $payerId  The payer/buyer ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The payer payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByPayer(int $payerId, array $filters = []): array
    {
        $filters['payer_id'] = $payerId;
        return $this->search($filters);
    }

    /**
     * Search payments with pagination.
     *
     * @param  array<string, mixed>  $filters  Search filters
     * @param  int  $limit  Number of results per page
     * @param  int  $offset  Results offset
     * @return array<string, mixed> The paginated results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function searchPaginated(array $filters = [], int $limit = 50, int $offset = 0): array
    {
        $filters['limit'] = $limit;
        $filters['offset'] = $offset;
        
        return $this->search($filters);
    }

    /**
     * Get account money payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The account money payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAccountMoneyPayments(array $filters = []): array
    {
        return $this->getByPaymentMethod('account_money', $filters);
    }

    /**
     * Get credit card payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The credit card payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getCreditCardPayments(array $filters = []): array
    {
        $filters['payment_type'] = 'credit_card';
        return $this->search($filters);
    }

    /**
     * Get debit card payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The debit card payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getDebitCardPayments(array $filters = []): array
    {
        $filters['payment_type'] = 'debit_card';
        return $this->search($filters);
    }

    /**
     * Get bank transfer payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The bank transfer payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getBankTransferPayments(array $filters = []): array
    {
        $filters['payment_type'] = 'bank_transfer';
        return $this->search($filters);
    }

    /**
     * Get ticket payments (boleto, etc.).
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The ticket payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getTicketPayments(array $filters = []): array
    {
        $filters['payment_type'] = 'ticket';
        return $this->search($filters);
    }

    /**
     * Get payments with refund account money tag.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return array<string, mixed> The refund account money payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getRefundAccountMoneyPayments(array $filters = []): array
    {
        $filters['tags'] = 'refund_account_money';
        return $this->search($filters);
    }
}
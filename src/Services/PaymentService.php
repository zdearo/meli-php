<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
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
     * @return Response The payment details
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function get(int $paymentId): Response
    {
        return ApiRequest::get("collections/{$paymentId}")
            ->send();
    }

    /**
     * Search payments with filters.
     *
     * @param  array<string, mixed>  $filters  The search filters
     * @return Response The search results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function search(array $filters = []): Response
    {
        return ApiRequest::get('payments/search')
            ->withQuery($filters)
            ->send();
    }

    /**
     * Get payments by order ID.
     *
     * @param  int  $orderId  The order ID
     * @return Response The order payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByOrder(int $orderId): Response
    {
        return $this->search(['order_id' => $orderId]);
    }

    /**
     * Get payments by external reference.
     *
     * @param  string  $externalReference  The external reference
     * @return Response The payments with that reference
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByExternalReference(string $externalReference): Response
    {
        return $this->search(['external_reference' => $externalReference]);
    }

    /**
     * Get payments by status.
     *
     * @param  string  $status  The payment status (approved, rejected, pending, etc.)
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The filtered payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByStatus(string $status, array $filters = []): Response
    {
        $filters['status'] = $status;

        return $this->search($filters);
    }

    /**
     * Get approved payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The approved payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getApprovedPayments(array $filters = []): Response
    {
        return $this->getByStatus('approved', $filters);
    }

    /**
     * Get pending payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The pending payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getPendingPayments(array $filters = []): Response
    {
        return $this->getByStatus('pending', $filters);
    }

    /**
     * Get rejected payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The rejected payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getRejectedPayments(array $filters = []): Response
    {
        return $this->getByStatus('rejected', $filters);
    }

    /**
     * Get cancelled payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The cancelled payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getCancelledPayments(array $filters = []): Response
    {
        return $this->getByStatus('cancelled', $filters);
    }

    /**
     * Get refunded payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The refunded payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getRefundedPayments(array $filters = []): Response
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
     * @return Response The filtered payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): Response
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
     * @return Response The filtered payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByPaymentMethod(string $paymentMethodId, array $filters = []): Response
    {
        $filters['payment_method_id'] = $paymentMethodId;

        return $this->search($filters);
    }

    /**
     * Get payments by collector (seller).
     *
     * @param  int  $collectorId  The collector/seller ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The collector payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByCollector(int $collectorId, array $filters = []): Response
    {
        $filters['collector_id'] = $collectorId;

        return $this->search($filters);
    }

    /**
     * Get payments by payer.
     *
     * @param  int  $payerId  The payer/buyer ID
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The payer payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getByPayer(int $payerId, array $filters = []): Response
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
     * @return Response The paginated results
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function searchPaginated(array $filters = [], int $limit = 50, int $offset = 0): Response
    {
        $filters['limit'] = $limit;
        $filters['offset'] = $offset;

        return $this->search($filters);
    }

    /**
     * Get account money payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The account money payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAccountMoneyPayments(array $filters = []): Response
    {
        return $this->getByPaymentMethod('account_money', $filters);
    }

    /**
     * Get credit card payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The credit card payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getCreditCardPayments(array $filters = []): Response
    {
        $filters['payment_type'] = 'credit_card';

        return $this->search($filters);
    }

    /**
     * Get debit card payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The debit card payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getDebitCardPayments(array $filters = []): Response
    {
        $filters['payment_type'] = 'debit_card';

        return $this->search($filters);
    }

    /**
     * Get bank transfer payments.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The bank transfer payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getBankTransferPayments(array $filters = []): Response
    {
        $filters['payment_type'] = 'bank_transfer';

        return $this->search($filters);
    }

    /**
     * Get ticket payments (boleto, etc.).
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The ticket payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getTicketPayments(array $filters = []): Response
    {
        $filters['payment_type'] = 'ticket';

        return $this->search($filters);
    }

    /**
     * Get payments with refund account money tag.
     *
     * @param  array<string, mixed>  $filters  Additional filters
     * @return Response The refund account money payments
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getRefundAccountMoneyPayments(array $filters = []): Response
    {
        $filters['tags'] = 'refund_account_money';

        return $this->search($filters);
    }
}

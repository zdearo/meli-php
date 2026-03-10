<?php

namespace Zdearo\Meli\DTO;

readonly class Payment
{
    public function __construct(
        public int $id,
        public ?int $orderId,
        public ?int $payerId,
        public ?string $status,
        public ?string $statusDetail,
        public ?string $paymentMethodId,
        public ?string $paymentType,
        public ?float $transactionAmount,
        public ?float $totalPaidAmount,
        public ?string $currencyId,
        public ?string $dateCreated,
        public ?string $dateApproved,
        public ?int $installments,
        public array $extra = [],
    ) {}

    public static function fromArray(array $data): static
    {
        $known = [
            'id', 'order_id', 'payer_id', 'status', 'status_detail',
            'payment_method_id', 'payment_type', 'transaction_amount',
            'total_paid_amount', 'currency_id', 'date_created',
            'date_approved', 'installments',
        ];

        return new static(
            id: $data['id'],
            orderId: $data['order_id'] ?? null,
            payerId: $data['payer_id'] ?? null,
            status: $data['status'] ?? null,
            statusDetail: $data['status_detail'] ?? null,
            paymentMethodId: $data['payment_method_id'] ?? null,
            paymentType: $data['payment_type'] ?? null,
            transactionAmount: $data['transaction_amount'] ?? null,
            totalPaidAmount: $data['total_paid_amount'] ?? null,
            currencyId: $data['currency_id'] ?? null,
            dateCreated: $data['date_created'] ?? null,
            dateApproved: $data['date_approved'] ?? null,
            installments: $data['installments'] ?? null,
            extra: array_diff_key($data, array_flip($known)),
        );
    }
}

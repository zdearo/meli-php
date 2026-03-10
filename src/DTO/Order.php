<?php

namespace Zdearo\Meli\DTO;

readonly class Order
{
    /**
     * @param  array<OrderItem>  $orderItems
     */
    public function __construct(
        public int $id,
        public ?string $status,
        public ?string $statusDetail,
        public ?string $dateCreated,
        public ?string $dateClosed,
        public ?string $dateLastUpdated,
        public ?float $totalAmount,
        public ?string $currencyId,
        public array $buyer = [],
        public array $seller = [],
        public array $orderItems = [],
        public array $payments = [],
        public array $shipping = [],
        public array $tags = [],
        public array $extra = [],
    ) {}

    public static function fromArray(array $data): static
    {
        $known = [
            'id', 'status', 'status_detail', 'date_created', 'date_closed',
            'date_last_updated', 'total_amount', 'currency_id', 'buyer',
            'seller', 'order_items', 'payments', 'shipping', 'tags',
        ];

        $orderItems = array_map(
            fn (array $item) => OrderItem::fromArray($item),
            $data['order_items'] ?? []
        );

        return new static(
            id: $data['id'],
            status: $data['status'] ?? null,
            statusDetail: $data['status_detail'] ?? null,
            dateCreated: $data['date_created'] ?? null,
            dateClosed: $data['date_closed'] ?? null,
            dateLastUpdated: $data['date_last_updated'] ?? null,
            totalAmount: $data['total_amount'] ?? null,
            currencyId: $data['currency_id'] ?? null,
            buyer: $data['buyer'] ?? [],
            seller: $data['seller'] ?? [],
            orderItems: $orderItems,
            payments: $data['payments'] ?? [],
            shipping: $data['shipping'] ?? [],
            tags: $data['tags'] ?? [],
            extra: array_diff_key($data, array_flip($known)),
        );
    }
}

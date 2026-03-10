<?php

namespace Zdearo\Meli\DTO;

readonly class OrderItem
{
    public function __construct(
        public array $item,
        public int $quantity,
        public float $unitPrice,
        public ?string $currencyId,
        public array $extra = [],
    ) {}

    public static function fromArray(array $data): static
    {
        $known = ['item', 'quantity', 'unit_price', 'currency_id'];

        return new static(
            item: $data['item'] ?? [],
            quantity: $data['quantity'] ?? 0,
            unitPrice: $data['unit_price'] ?? 0,
            currencyId: $data['currency_id'] ?? null,
            extra: array_diff_key($data, array_flip($known)),
        );
    }
}

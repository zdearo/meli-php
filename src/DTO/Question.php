<?php

namespace Zdearo\Meli\DTO;

readonly class Question
{
    public function __construct(
        public int $id,
        public ?string $itemId,
        public ?int $sellerId,
        public ?string $status,
        public ?string $text,
        public ?string $dateCreated,
        public array $from = [],
        public ?array $answer = null,
        public array $extra = [],
    ) {}

    public static function fromArray(array $data): static
    {
        $known = [
            'id', 'item_id', 'seller_id', 'status', 'text',
            'date_created', 'from', 'answer',
        ];

        return new static(
            id: $data['id'],
            itemId: $data['item_id'] ?? null,
            sellerId: $data['seller_id'] ?? null,
            status: $data['status'] ?? null,
            text: $data['text'] ?? null,
            dateCreated: $data['date_created'] ?? null,
            from: $data['from'] ?? [],
            answer: $data['answer'] ?? null,
            extra: array_diff_key($data, array_flip($known)),
        );
    }
}

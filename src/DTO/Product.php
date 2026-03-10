<?php

namespace Zdearo\Meli\DTO;

readonly class Product
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $categoryId,
        public ?int $sellerId,
        public ?float $price,
        public ?string $currencyId,
        public ?int $availableQuantity,
        public ?int $soldQuantity,
        public ?string $condition,
        public ?string $status,
        public ?string $permalink,
        public ?string $thumbnail,
        public ?string $listingTypeId,
        public ?string $dateCreated,
        public ?string $lastUpdated,
        public array $pictures = [],
        public array $attributes = [],
        public array $variations = [],
        public array $shipping = [],
        public array $extra = [],
    ) {}

    public static function fromArray(array $data): static
    {
        $known = [
            'id', 'title', 'category_id', 'seller_id', 'price', 'currency_id',
            'available_quantity', 'sold_quantity', 'condition', 'status', 'permalink',
            'thumbnail', 'listing_type_id', 'date_created', 'last_updated',
            'pictures', 'attributes', 'variations', 'shipping',
        ];

        return new static(
            id: $data['id'],
            title: $data['title'] ?? '',
            categoryId: $data['category_id'] ?? null,
            sellerId: $data['seller_id'] ?? null,
            price: $data['price'] ?? null,
            currencyId: $data['currency_id'] ?? null,
            availableQuantity: $data['available_quantity'] ?? null,
            soldQuantity: $data['sold_quantity'] ?? null,
            condition: $data['condition'] ?? null,
            status: $data['status'] ?? null,
            permalink: $data['permalink'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            listingTypeId: $data['listing_type_id'] ?? null,
            dateCreated: $data['date_created'] ?? null,
            lastUpdated: $data['last_updated'] ?? null,
            pictures: $data['pictures'] ?? [],
            attributes: $data['attributes'] ?? [],
            variations: $data['variations'] ?? [],
            shipping: $data['shipping'] ?? [],
            extra: array_diff_key($data, array_flip($known)),
        );
    }
}

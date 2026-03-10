<?php

namespace Zdearo\Meli\DTO;

readonly class User
{
    public function __construct(
        public int $id,
        public ?string $nickname,
        public ?string $firstName,
        public ?string $lastName,
        public ?string $email,
        public ?string $countryId,
        public ?string $siteId,
        public ?string $registrationDate,
        public ?string $permalink,
        public array $address = [],
        public array $phone = [],
        public array $sellerReputation = [],
        public array $buyerReputation = [],
        public array $tags = [],
        public array $extra = [],
    ) {}

    public static function fromArray(array $data): static
    {
        $known = [
            'id', 'nickname', 'first_name', 'last_name', 'email', 'country_id',
            'site_id', 'registration_date', 'permalink', 'address', 'phone',
            'seller_reputation', 'buyer_reputation', 'tags',
        ];

        return new static(
            id: $data['id'],
            nickname: $data['nickname'] ?? null,
            firstName: $data['first_name'] ?? null,
            lastName: $data['last_name'] ?? null,
            email: $data['email'] ?? null,
            countryId: $data['country_id'] ?? null,
            siteId: $data['site_id'] ?? null,
            registrationDate: $data['registration_date'] ?? null,
            permalink: $data['permalink'] ?? null,
            address: $data['address'] ?? [],
            phone: $data['phone'] ?? [],
            sellerReputation: $data['seller_reputation'] ?? [],
            buyerReputation: $data['buyer_reputation'] ?? [],
            tags: $data['tags'] ?? [],
            extra: array_diff_key($data, array_flip($known)),
        );
    }
}

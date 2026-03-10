<?php

namespace Zdearo\Meli\DTO;

readonly class AuthToken
{
    public function __construct(
        public string $accessToken,
        public string $tokenType,
        public int $expiresIn,
        public ?string $refreshToken,
        public ?string $scope,
        public ?int $userId,
        public array $extra = [],
    ) {}

    public static function fromArray(array $data): static
    {
        $known = ['access_token', 'token_type', 'expires_in', 'refresh_token', 'scope', 'user_id'];

        return new static(
            accessToken: $data['access_token'],
            tokenType: $data['token_type'] ?? 'Bearer',
            expiresIn: $data['expires_in'] ?? 0,
            refreshToken: $data['refresh_token'] ?? null,
            scope: $data['scope'] ?? null,
            userId: $data['user_id'] ?? null,
            extra: array_diff_key($data, array_flip($known)),
        );
    }
}

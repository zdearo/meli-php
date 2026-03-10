<?php

namespace Zdearo\Meli\Exceptions;

use Illuminate\Http\Client\Response;

class MeliException extends \RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $statusCode,
        public readonly string $errorCode,
        public readonly array $context,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
    }

    public static function fromResponse(Response $response, ?\Throwable $previous = null): static
    {
        $body = $response->json() ?? [];

        return new static(
            message: $body['message'] ?? $response->body(),
            statusCode: $response->status(),
            errorCode: $body['error'] ?? 'unknown',
            context: $body,
            previous: $previous,
        );
    }
}

<?php

namespace Zdearo\Meli\Exceptions;

use Illuminate\Http\Client\Response;

class MeliValidationException extends MeliException
{
    /** @var array<int, array{code: string, message: string}> */
    public array $causes = [];

    public static function fromResponse(Response $response, ?\Throwable $previous = null): static
    {
        $body = $response->json() ?? [];

        $instance = new static(
            message: $body['message'] ?? $response->body(),
            statusCode: $response->status(),
            errorCode: $body['error'] ?? 'validation_error',
            context: $body,
            previous: $previous,
        );

        $instance->causes = $body['cause'] ?? [];

        return $instance;
    }
}

<?php

namespace Zdearo\Meli\Http;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Zdearo\Meli\Exceptions\ApiException;

class ResponseHandler 
{
    /**
     * Handle a successful API response.
     *
     * @param ResponseInterface $response The API response
     * @return array<string, mixed> The decoded response body
     */
    public static function handleResponse(ResponseInterface $response): array 
    {
        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    /**
     * Handle an API request exception.
     *
     * @param RequestException $exception The request exception
     * @throws ApiException
     * @return void
     */
    public static function handleException(RequestException $exception): void
    {
        $response = $exception->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 0;
        $reasonPhrase = $response ? $response->getReasonPhrase() : 'Unknown Error';
        $body = $response ? json_decode($response->getBody()->getContents(), true) : null;
      
        $message = "Mercado Libre API Error [{$statusCode}]: {$reasonPhrase}";

        if (is_array($body) && isset($body['message'])) {
            $message .= " - " . $body['message'];
        }

        throw new ApiException($message, $statusCode);
    }
}

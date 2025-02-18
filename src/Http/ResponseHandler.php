<?php

namespace Zdearo\Meli\Http;

use GuzzleHttp\Exception\RequestException;
use Zdearo\Meli\Exceptions\ApiException;

class ResponseHandler {
    public static function handleResponse($response) {
        return json_decode($response->getBody(), true);
    }

    public static function handleException(RequestException $exception)
    {
        $response = $exception->getResponse();
        $statusCode = $response ? $response->getStatusCode() : 0;
        $reasonPhrase = $response ? $response->getReasonPhrase() : 'Unknown Error';
        $body = $response ? json_decode($response->getBody()->getContents(), true) : null;

        $message = "Erro na API Mercado Livre [{$statusCode}]: {$reasonPhrase}";

        if (is_array($body) && isset($body['message'])) {
            $message .= " - " . $body['message'];
        }

        throw new ApiException($message, $statusCode);
    }
}

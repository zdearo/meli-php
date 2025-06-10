<?php

use Zdearo\Meli\Http\ResponseHandler;
use Zdearo\Meli\Exceptions\ApiException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

test('can handle successful response', function () {
    $responseBody = json_encode(['key' => 'value']);
    $response = new Response(200, [], $responseBody);
    
    $result = ResponseHandler::handleResponse($response);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('key');
    expect($result['key'])->toBe('value');
});

test('can handle empty response', function () {
    $response = new Response(204);
    
    $result = ResponseHandler::handleResponse($response);
    
    expect($result)->toBeArray();
    expect($result)->toBeEmpty();
});

test('throws api exception for request exception with response', function () {
    $responseBody = json_encode(['message' => 'Not found']);
    $response = new Response(404, [], $responseBody);
    $request = new Request('GET', 'test');
    $exception = new RequestException('Error', $request, $response);
    
    expect(fn() => ResponseHandler::handleException($exception))
        ->toThrow(ApiException::class, 'Mercado Libre API Error [404]: Not Found - Not found');
});

test('throws api exception for request exception without response', function () {
    $request = new Request('GET', 'test');
    $exception = new RequestException('Error', $request);
    
    expect(fn() => ResponseHandler::handleException($exception))
        ->toThrow(ApiException::class, 'Mercado Libre API Error [0]: Unknown Error');
});

test('throws api exception with correct status code', function () {
    $responseBody = json_encode(['message' => 'Not found']);
    $response = new Response(404, [], $responseBody);
    $request = new Request('GET', 'test');
    $exception = new RequestException('Error', $request, $response);
    
    try {
        ResponseHandler::handleException($exception);
    } catch (ApiException $e) {
        expect($e->getStatusCode())->toBe(404);
    }
});

test('throws api exception with correct message when response body has no message', function () {
    $responseBody = json_encode(['error' => true]);
    $response = new Response(400, [], $responseBody);
    $request = new Request('GET', 'test');
    $exception = new RequestException('Error', $request, $response);
    
    expect(fn() => ResponseHandler::handleException($exception))
        ->toThrow(ApiException::class, 'Mercado Libre API Error [400]: Bad Request');
});
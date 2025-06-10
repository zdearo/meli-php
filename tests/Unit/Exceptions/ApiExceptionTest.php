<?php

use Zdearo\Meli\Exceptions\ApiException;

test('can create api exception with message and status code', function () {
    $exception = new ApiException('Test message', 400);
    
    expect($exception->getMessage())->toBe('Test message');
    expect($exception->getStatusCode())->toBe(400);
    expect($exception->getCode())->toBe(400);
});

test('can create api exception with default status code', function () {
    $exception = new ApiException('Test message');
    
    expect($exception->getMessage())->toBe('Test message');
    expect($exception->getStatusCode())->toBe(500);
    expect($exception->getCode())->toBe(500);
});

test('api exception extends php exception', function () {
    $exception = new ApiException('Test message');
    
    expect($exception)->toBeInstanceOf(\Exception::class);
});
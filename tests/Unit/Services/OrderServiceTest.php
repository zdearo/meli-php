<?php

use Zdearo\Meli\Services\OrderService;

// Mock functions for testing
if (! function_exists('app')) {
    function app(?string $abstract = null)
    {
        if ($abstract === 'meli.client') {
            return new \Zdearo\Meli\Support\MeliApiClient;
        }
        return null;
    }
}

if (! function_exists('config')) {
    function config(string $key, $default = null)
    {
        $configs = [
            'meli.client_id' => 'test-client-id',
            'meli.client_secret' => 'test-client-secret',
            'meli.redirect_uri' => 'https://example.com/callback',
            'meli.auth_domain' => 'mercadolibre.com.br',
            'meli.base_url' => 'https://api.mercadolibre.com/',
            'meli.api_token' => 'test-api-token',
        ];
        return $configs[$key] ?? $default;
    }
}

test('can create order service instance', function () {
    $service = new OrderService;

    expect($service)->toBeInstanceOf(OrderService::class);
});

test('service methods exist and are callable', function () {
    $service = new OrderService;

    expect(method_exists($service, 'get'))->toBeTrue();
    expect(method_exists($service, 'search'))->toBeTrue();
    expect(method_exists($service, 'getBySeller'))->toBeTrue();
    expect(method_exists($service, 'getByBuyer'))->toBeTrue();
    expect(method_exists($service, 'getByStatus'))->toBeTrue();
    expect(method_exists($service, 'getByDateRange'))->toBeTrue();
    expect(method_exists($service, 'getByTags'))->toBeTrue();
    expect(method_exists($service, 'getProductInfo'))->toBeTrue();
    expect(method_exists($service, 'getDiscounts'))->toBeTrue();
    expect(method_exists($service, 'getPaidOrders'))->toBeTrue();
});
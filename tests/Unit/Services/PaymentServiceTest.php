<?php

use Zdearo\Meli\Services\PaymentService;

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

test('can create payment service instance', function () {
    $service = new PaymentService;

    expect($service)->toBeInstanceOf(PaymentService::class);
});

test('service methods exist and are callable', function () {
    $service = new PaymentService;

    expect(method_exists($service, 'get'))->toBeTrue();
    expect(method_exists($service, 'search'))->toBeTrue();
    expect(method_exists($service, 'getByOrder'))->toBeTrue();
    expect(method_exists($service, 'getByExternalReference'))->toBeTrue();
    expect(method_exists($service, 'getByStatus'))->toBeTrue();
    expect(method_exists($service, 'getApprovedPayments'))->toBeTrue();
    expect(method_exists($service, 'getPendingPayments'))->toBeTrue();
    expect(method_exists($service, 'getRejectedPayments'))->toBeTrue();
    expect(method_exists($service, 'getByDateRange'))->toBeTrue();
    expect(method_exists($service, 'getByPaymentMethod'))->toBeTrue();
    expect(method_exists($service, 'getAccountMoneyPayments'))->toBeTrue();
});

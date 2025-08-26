<?php

use Zdearo\Meli\Services\UserService;

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

test('can create user service instance', function () {
    $service = new UserService;

    expect($service)->toBeInstanceOf(UserService::class);
});

test('service methods exist and are callable', function () {
    $service = new UserService;

    expect(method_exists($service, 'get'))->toBeTrue();
    expect(method_exists($service, 'me'))->toBeTrue();
    expect(method_exists($service, 'update'))->toBeTrue();
    expect(method_exists($service, 'getAddresses'))->toBeTrue();
    expect(method_exists($service, 'getAcceptedPaymentMethods'))->toBeTrue();
    expect(method_exists($service, 'getBrands'))->toBeTrue();
    expect(method_exists($service, 'getAvailableListingTypes'))->toBeTrue();
    expect(method_exists($service, 'getClassifiedsPromotionPacks'))->toBeTrue();
});
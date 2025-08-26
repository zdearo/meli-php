<?php

use Zdearo\Meli\Services\CategoryService;

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

test('can create category service instance', function () {
    $service = new CategoryService;

    expect($service)->toBeInstanceOf(CategoryService::class);
});

test('service methods exist and are callable', function () {
    $service = new CategoryService;

    expect(method_exists($service, 'getSites'))->toBeTrue();
    expect(method_exists($service, 'getCategories'))->toBeTrue();
    expect(method_exists($service, 'get'))->toBeTrue();
    expect(method_exists($service, 'getAttributes'))->toBeTrue();
    expect(method_exists($service, 'getListingExposures'))->toBeTrue();
    expect(method_exists($service, 'getListingPrices'))->toBeTrue();
    expect(method_exists($service, 'predictCategory'))->toBeTrue();
    expect(method_exists($service, 'getDomainTechnicalSpecs'))->toBeTrue();
});
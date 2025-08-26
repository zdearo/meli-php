<?php

use Zdearo\Meli\Services\CategoryService;

// Mock app function for Laravel service container
if (! function_exists('app')) {
    function app(?string $abstract = null)
    {
        if ($abstract === 'meli.client') {
            return new \Zdearo\Meli\Support\MeliApiClient;
        }

        return null;
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
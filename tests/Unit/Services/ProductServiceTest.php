<?php

use Zdearo\Meli\Services\ProductService;

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

test('can create product service instance', function () {
    $service = new ProductService;

    expect($service)->toBeInstanceOf(ProductService::class);
});

test('service methods exist and are callable', function () {
    $service = new ProductService;

    expect(method_exists($service, 'create'))->toBeTrue();
    expect(method_exists($service, 'get'))->toBeTrue();
    expect(method_exists($service, 'update'))->toBeTrue();
    expect(method_exists($service, 'changeStatus'))->toBeTrue();
});

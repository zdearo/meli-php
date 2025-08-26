<?php

use Zdearo\Meli\Services\OrderService;

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
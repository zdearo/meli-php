<?php

use Zdearo\Meli\Services\PaymentService;

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
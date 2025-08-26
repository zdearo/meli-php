<?php

use Zdearo\Meli\Services\UserService;

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
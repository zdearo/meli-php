<?php

use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Services\SearchItemService;

test('can create search item service instance', function () {
    $service = new SearchItemService(MarketplaceEnum::BRASIL);

    expect($service)->toBeInstanceOf(SearchItemService::class);
});

test('service has search methods', function () {
    $service = new SearchItemService(MarketplaceEnum::BRASIL);

    expect(method_exists($service, 'byQuery'))->toBeTrue();
    expect(method_exists($service, 'byCategory'))->toBeTrue();
    expect(method_exists($service, 'byNickname'))->toBeTrue();
    expect(method_exists($service, 'bySeller'))->toBeTrue();
    expect(method_exists($service, 'byUserItems'))->toBeTrue();
    expect(method_exists($service, 'multiGetItems'))->toBeTrue();
    expect(method_exists($service, 'multiGetUsers'))->toBeTrue();
});

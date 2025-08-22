<?php

use Zdearo\Meli\Services\VisitsService;

test('can create visits service instance', function () {
    $service = new VisitsService;

    expect($service)->toBeInstanceOf(VisitsService::class);
});

test('service has visits methods', function () {
    $service = new VisitsService;

    expect(method_exists($service, 'totalByUser'))->toBeTrue();
    expect(method_exists($service, 'totalByItem'))->toBeTrue();
    expect(method_exists($service, 'totalByItemsDateRange'))->toBeTrue();
    expect(method_exists($service, 'visitsByUserTimeWindow'))->toBeTrue();
    expect(method_exists($service, 'visitsByItemTimeWindow'))->toBeTrue();
});

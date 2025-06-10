<?php

use Zdearo\Meli\Meli;
use Zdearo\Meli\Services\AuthService;
use Zdearo\Meli\Services\SearchItemService;
use Zdearo\Meli\Services\ProductService;
use Zdearo\Meli\Services\VisitsService;

test('can create meli instance', function () {
    $meli = createMeli();
    
    expect($meli)->toBeInstanceOf(Meli::class);
});

test('can get region from meli instance', function () {
    $meli = createMeli('BRASIL');
    
    expect($meli->getRegion())->toBe('MLB');
});

test('can get auth service from meli instance', function () {
    $meli = createMeli();
    
    expect($meli->auth())->toBeInstanceOf(AuthService::class);
});

test('can get search items service from meli instance', function () {
    $meli = createMeli();
    
    expect($meli->searchItems())->toBeInstanceOf(SearchItemService::class);
});

test('can get products service from meli instance', function () {
    $meli = createMeli();
    
    expect($meli->products())->toBeInstanceOf(ProductService::class);
});

test('can get visits service from meli instance', function () {
    $meli = createMeli();
    
    expect($meli->visits())->toBeInstanceOf(VisitsService::class);
});

test('throws exception when invalid region is provided', function () {
    expect(fn() => createMeli('INVALID_REGION'))->toThrow(\Error::class);
});
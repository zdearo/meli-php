<?php

use Zdearo\Meli\Support\MeliApiClient;

test('can create meli api client instance', function () {
    $client = new MeliApiClient;

    expect($client)->toBeInstanceOf(MeliApiClient::class);
});

test('generates unique state values', function () {
    $state1 = MeliApiClient::generateState();
    $state2 = MeliApiClient::generateState();

    expect($state1)->not->toBe($state2);
    expect(strlen($state1))->toBeGreaterThan(10);
    expect(strlen($state2))->toBeGreaterThan(10);
});

test('client has required methods', function () {
    $client = new MeliApiClient;

    expect(method_exists($client, 'send'))->toBeTrue();
    expect(method_exists(MeliApiClient::class, 'getAuthUrl'))->toBeTrue();
    expect(method_exists(MeliApiClient::class, 'generateState'))->toBeTrue();
});

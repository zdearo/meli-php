<?php

use Zdearo\Meli\Http\MeliClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery as m;

test('can create meli client instance', function () {
    $client = createMeliClient();
    
    expect($client)->toBeInstanceOf(MeliClient::class);
});

test('can get guzzle client from meli client', function () {
    $client = createMeliClient();
    
    expect($client->getClient())->toBeInstanceOf(Client::class);
});

test('client has correct base uri', function () {
    $client = createMeliClient();
    $guzzleClient = $client->getClient();
    
    $config = $guzzleClient->getConfig();
    expect($config['base_uri']->__toString())->toBe('https://api.mercadolibre.com/');
});

test('client has correct headers with api token', function () {
    $client = createMeliClient('test-token');
    $guzzleClient = $client->getClient();
    
    $config = $guzzleClient->getConfig();
    expect($config['headers']['authorization'])->toBe('Bearer test-token');
    expect($config['headers']['accept'])->toBe('application/json');
    expect($config['headers']['content-type'])->toBe('application/json');
});

test('client has correct headers without api token', function () {
    $client = createMeliClient();
    $guzzleClient = $client->getClient();
    
    $config = $guzzleClient->getConfig();
    expect($config['headers']['authorization'])->toBe('');
    expect($config['headers']['accept'])->toBe('application/json');
    expect($config['headers']['content-type'])->toBe('application/json');
});

test('client has correct timeout', function () {
    $client = createMeliClient('', 15.0);
    $guzzleClient = $client->getClient();
    
    $config = $guzzleClient->getConfig();
    expect($config['timeout'])->toBe(15.0);
});

test('can handle request exception', function () {
    $client = createMeliClient();
    
    $response = new Response(404, [], json_encode(['message' => 'Not found']));
    $request = new Request('GET', 'test');
    $exception = new RequestException('Error', $request, $response);
    
    $result = $client->handleRequestException($exception);
    
    expect($result)->toBeArray();
    expect($result['error'])->toBeTrue();
    expect($result['status_code'])->toBe(404);
    expect($result['message'])->toBe('Error');
    expect($result['response'])->toBeArray();
    expect($result['response']['message'])->toBe('Not found');
});

test('can handle request exception without response', function () {
    $client = createMeliClient();
    
    $request = new Request('GET', 'test');
    $exception = new RequestException('Error', $request);
    
    $result = $client->handleRequestException($exception);
    
    expect($result)->toBeArray();
    expect($result['error'])->toBeTrue();
    expect($result['status_code'])->toBe(500);
    expect($result['message'])->toBe('Error');
    expect($result['response'])->toBeArray();
});
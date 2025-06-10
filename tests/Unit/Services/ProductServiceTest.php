<?php

use Zdearo\Meli\Services\ProductService;
use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery as m;

test('can create product service instance', function () {
    $client = createMeliClient();
    $service = new ProductService($client);
    
    expect($service)->toBeInstanceOf(ProductService::class);
});

test('can create a product', function () {
    // Create a mock response
    $responseData = [
        'id' => 'MLB123',
        'title' => 'Test Product',
        'price' => 100,
        'status' => 'active',
    ];
    
    $mock = new MockHandler([
        new Response(201, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new ProductService($meliClient);
    
    $productData = [
        'title' => 'Test Product',
        'category_id' => 'MLB1055',
        'price' => 100,
        'currency_id' => 'BRL',
        'available_quantity' => 10,
        'buying_mode' => 'buy_it_now',
        'listing_type_id' => 'gold_special',
        'condition' => 'new',
        'description' => 'Test product description',
        'pictures' => [
            ['source' => 'http://example.com/image.jpg'],
        ],
    ];
    
    $result = $service->create($productData);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('id');
    expect($result['id'])->toBe('MLB123');
    expect($result['title'])->toBe('Test Product');
    expect($result['price'])->toBe(100);
    expect($result['status'])->toBe('active');
});

test('can get a product', function () {
    // Create a mock response
    $responseData = [
        'id' => 'MLB123',
        'title' => 'Test Product',
        'price' => 100,
        'status' => 'active',
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new ProductService($meliClient);
    $result = $service->get('MLB123');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('id');
    expect($result['id'])->toBe('MLB123');
    expect($result['title'])->toBe('Test Product');
});

test('can update a product', function () {
    // Create a mock response
    $responseData = [
        'id' => 'MLB123',
        'title' => 'Updated Product',
        'price' => 150,
        'status' => 'active',
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new ProductService($meliClient);
    
    $updateData = [
        'title' => 'Updated Product',
        'price' => 150,
    ];
    
    $result = $service->update('MLB123', $updateData);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('id');
    expect($result['id'])->toBe('MLB123');
    expect($result['title'])->toBe('Updated Product');
    expect($result['price'])->toBe(150);
});

test('can change product status', function () {
    // Create a mock response
    $responseData = [
        'id' => 'MLB123',
        'title' => 'Test Product',
        'status' => 'paused',
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new ProductService($meliClient);
    $result = $service->changeStatus('MLB123', 'paused');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('id');
    expect($result['id'])->toBe('MLB123');
    expect($result['status'])->toBe('paused');
});

test('throws api exception on product error', function () {
    // Create a mock response for an error
    $mock = new MockHandler([
        new Response(404, [], json_encode(['message' => 'Item not found'])),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new ProductService($meliClient);
    
    expect(fn() => $service->get('MLB999'))->toThrow(ApiException::class);
});
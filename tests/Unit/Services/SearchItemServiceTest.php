<?php

use Zdearo\Meli\Services\SearchItemService;
use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery as m;

test('can create search item service instance', function () {
    $client = createMeliClient();
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $client);
    
    expect($service)->toBeInstanceOf(SearchItemService::class);
});

test('can search items by query', function () {
    // Create a mock response
    $responseData = [
        'results' => [
            ['id' => 'MLB123', 'title' => 'Item 1'],
            ['id' => 'MLB456', 'title' => 'Item 2'],
        ],
        'paging' => [
            'total' => 2,
            'offset' => 0,
            'limit' => 50,
        ],
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->byQuery('smartphone');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('results');
    expect($result['results'])->toBeArray();
    expect($result['results'])->toHaveCount(2);
    expect($result['results'][0]['title'])->toBe('Item 1');
});

test('can search items by category', function () {
    // Create a mock response
    $responseData = [
        'results' => [
            ['id' => 'MLB123', 'title' => 'Item 1'],
            ['id' => 'MLB456', 'title' => 'Item 2'],
        ],
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->byCategory('MLB1051');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('results');
});

test('can search items by nickname', function () {
    // Create a mock response
    $responseData = [
        'results' => [
            ['id' => 'MLB123', 'title' => 'Item 1'],
        ],
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->byNickname('SELLER_NICKNAME');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('results');
});

test('can search items by seller id', function () {
    // Create a mock response
    $responseData = [
        'results' => [
            ['id' => 'MLB123', 'title' => 'Item 1'],
        ],
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->bySeller(12345);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('results');
});

test('can search items by seller id with category', function () {
    // Create a mock response
    $responseData = [
        'results' => [
            ['id' => 'MLB123', 'title' => 'Item 1'],
        ],
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->bySeller(12345, 'MLB1051');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('results');
});

test('can search items by user id', function () {
    // Create a mock response
    $responseData = [
        'results' => ['MLB123', 'MLB456'],
        'paging' => [
            'total' => 2,
            'limit' => 50,
        ],
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->byUserItems(12345);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('results');
    expect($result['results'])->toBeArray();
});

test('can get multiple items', function () {
    // Create a mock response
    $responseData = [
        ['id' => 'MLB123', 'title' => 'Item 1'],
        ['id' => 'MLB456', 'title' => 'Item 2'],
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->multiGetItems(['MLB123', 'MLB456']);
    
    expect($result)->toBeArray();
    expect($result)->toHaveCount(2);
    expect($result[0]['id'])->toBe('MLB123');
    expect($result[1]['id'])->toBe('MLB456');
});

test('can get multiple users', function () {
    // Create a mock response
    $responseData = [
        ['id' => 123, 'nickname' => 'User 1'],
        ['id' => 456, 'nickname' => 'User 2'],
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new SearchItemService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->multiGetUsers([123, 456]);
    
    expect($result)->toBeArray();
    expect($result)->toHaveCount(2);
    expect($result[0]['id'])->toBe(123);
    expect($result[1]['id'])->toBe(456);
});
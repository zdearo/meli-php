<?php

use Zdearo\Meli\Services\VisitsService;
use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery as m;

test('can create visits service instance', function () {
    $client = createMeliClient();
    $service = new VisitsService($client);
    
    expect($service)->toBeInstanceOf(VisitsService::class);
});

test('can get total visits by user', function () {
    // Create a mock response
    $responseData = [
        'total_visits' => 1500,
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new VisitsService($meliClient);
    $result = $service->totalByUser(12345, '2023-01-01', '2023-01-31');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('total_visits');
    expect($result['total_visits'])->toBe(1500);
});

test('can get total visits by item', function () {
    // Create a mock response
    $responseData = [
        'MLB123' => 500,
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new VisitsService($meliClient);
    $result = $service->totalByItem('MLB123');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('MLB123');
    expect($result['MLB123'])->toBe(500);
});

test('can get total visits by items date range', function () {
    // Create a mock response
    $responseData = [
        'MLB123' => [
            'total' => 500,
            'dates' => [
                '2023-01-01' => 100,
                '2023-01-02' => 150,
                '2023-01-03' => 250,
            ],
        ],
        'MLB456' => [
            'total' => 300,
            'dates' => [
                '2023-01-01' => 50,
                '2023-01-02' => 100,
                '2023-01-03' => 150,
            ],
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
    
    $service = new VisitsService($meliClient);
    $result = $service->totalByItemsDateRange(['MLB123', 'MLB456'], '2023-01-01', '2023-01-03');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('MLB123');
    expect($result)->toHaveKey('MLB456');
    expect($result['MLB123']['total'])->toBe(500);
    expect($result['MLB456']['total'])->toBe(300);
    expect($result['MLB123']['dates'])->toHaveCount(3);
});

test('can get visits by user time window', function () {
    // Create a mock response
    $responseData = [
        'total_visits' => 1500,
        'visits_by_period' => [
            ['date' => '2023-01-01', 'visits' => 100],
            ['date' => '2023-01-02', 'visits' => 150],
            ['date' => '2023-01-03', 'visits' => 250],
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
    
    $service = new VisitsService($meliClient);
    $result = $service->visitsByUserTimeWindow(12345, 7, 'day');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('total_visits');
    expect($result)->toHaveKey('visits_by_period');
    expect($result['total_visits'])->toBe(1500);
    expect($result['visits_by_period'])->toHaveCount(3);
});

test('can get visits by item time window', function () {
    // Create a mock response
    $responseData = [
        'total_visits' => 500,
        'visits_by_period' => [
            ['date' => '2023-01-01', 'visits' => 100],
            ['date' => '2023-01-02', 'visits' => 150],
            ['date' => '2023-01-03', 'visits' => 250],
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
    
    $service = new VisitsService($meliClient);
    $result = $service->visitsByItemTimeWindow('MLB123', 7, 'day');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('total_visits');
    expect($result)->toHaveKey('visits_by_period');
    expect($result['total_visits'])->toBe(500);
    expect($result['visits_by_period'])->toHaveCount(3);
});

test('can get visits by item time window with ending date', function () {
    // Create a mock response
    $responseData = [
        'total_visits' => 500,
        'visits_by_period' => [
            ['date' => '2023-01-01', 'visits' => 100],
            ['date' => '2023-01-02', 'visits' => 150],
            ['date' => '2023-01-03', 'visits' => 250],
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
    
    $service = new VisitsService($meliClient);
    $result = $service->visitsByItemTimeWindow('MLB123', 7, 'day', '2023-01-03');
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('total_visits');
    expect($result['total_visits'])->toBe(500);
});

test('throws api exception on visits error', function () {
    // Create a mock response for an error
    $mock = new MockHandler([
        new Response(404, [], json_encode(['message' => 'User not found'])),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new VisitsService($meliClient);
    
    expect(fn() => $service->totalByUser(99999, '2023-01-01', '2023-01-31'))
        ->toThrow(ApiException::class);
});
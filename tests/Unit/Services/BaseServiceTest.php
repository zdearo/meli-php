<?php

use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Http\ResponseHandler;
use Zdearo\Meli\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Mockery as m;

// Create a concrete implementation of BaseService for testing
class TestService extends \Zdearo\Meli\Services\BaseService
{
    public function __construct(MeliClient $client)
    {
        parent::__construct($client);
    }
    
    public function testRequest(string $method, string $uri, array $data = []): array
    {
        return $this->request($method, $uri, $data);
    }
}

test('can create base service instance', function () {
    $client = createMeliClient();
    $service = new TestService($client);
    
    expect($service)->toBeInstanceOf(TestService::class);
});

test('can make successful GET request', function () {
    // Create a mock response
    $mock = new MockHandler([
        new Response(200, [], json_encode(['success' => true])),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new TestService($meliClient);
    $result = $service->testRequest('GET', 'test', ['param' => 'value']);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('success');
    expect($result['success'])->toBeTrue();
});

test('can make successful POST request', function () {
    // Create a mock response
    $mock = new MockHandler([
        new Response(201, [], json_encode(['id' => 123])),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new TestService($meliClient);
    $result = $service->testRequest('POST', 'test', ['data' => 'value']);
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('id');
    expect($result['id'])->toBe(123);
});

test('throws api exception on request error', function () {
    // Create a mock response for an error
    $request = new Request('GET', 'test');
    $response = new Response(404, [], json_encode(['message' => 'Not found']));
    $exception = new RequestException('Error', $request, $response);
    
    $mock = new MockHandler([
        $exception,
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new TestService($meliClient);
    
    expect(fn() => $service->testRequest('GET', 'test'))
        ->toThrow(ApiException::class);
});
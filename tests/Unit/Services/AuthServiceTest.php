<?php

use Zdearo\Meli\Services\AuthService;
use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Http\MeliClient;
use Zdearo\Meli\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use Mockery as m;

test('can create auth service instance', function () {
    $client = createMeliClient();
    $service = new AuthService(MarketplaceEnum::BRASIL, $client);
    
    expect($service)->toBeInstanceOf(AuthService::class);
});

test('can get auth url', function () {
    $client = createMeliClient();
    $service = new AuthService(MarketplaceEnum::BRASIL, $client);
    
    $url = $service->getAuthUrl(
        'https://example.com/callback',
        'client-id',
        'state-value'
    );
    
    expect($url)->toBe(
        'https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=client-id&redirect_uri=https://example.com/callback&state=state-value'
    );
});

test('can get token', function () {
    // Create a mock response
    $responseData = [
        'access_token' => 'access-token',
        'token_type' => 'bearer',
        'expires_in' => 21600,
        'refresh_token' => 'refresh-token',
        'scope' => 'offline_access read write',
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new AuthService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->getToken(
        'client-id',
        'client-secret',
        'auth-code',
        'https://example.com/callback'
    );
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('access_token');
    expect($result['access_token'])->toBe('access-token');
    expect($result)->toHaveKey('refresh_token');
    expect($result['refresh_token'])->toBe('refresh-token');
});

test('can refresh token', function () {
    // Create a mock response
    $responseData = [
        'access_token' => 'new-access-token',
        'token_type' => 'bearer',
        'expires_in' => 21600,
        'refresh_token' => 'new-refresh-token',
        'scope' => 'offline_access read write',
    ];
    
    $mock = new MockHandler([
        new Response(200, [], json_encode($responseData)),
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new AuthService(MarketplaceEnum::BRASIL, $meliClient);
    $result = $service->refreshToken(
        'client-id',
        'client-secret',
        'refresh-token'
    );
    
    expect($result)->toBeArray();
    expect($result)->toHaveKey('access_token');
    expect($result['access_token'])->toBe('new-access-token');
    expect($result)->toHaveKey('refresh_token');
    expect($result['refresh_token'])->toBe('new-refresh-token');
});

test('throws api exception on auth error', function () {
    // Create a mock response for an error
    $request = new Request('POST', 'oauth/token');
    $response = new Response(400, [], json_encode(['message' => 'Invalid client credentials']));
    $exception = new RequestException('Error', $request, $response);
    
    $mock = new MockHandler([
        $exception,
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $guzzleClient = new Client(['handler' => $handlerStack]);
    
    // Mock the MeliClient to return our mocked Guzzle client
    $meliClient = m::mock(MeliClient::class);
    $meliClient->shouldReceive('getClient')->andReturn($guzzleClient);
    
    $service = new AuthService(MarketplaceEnum::BRASIL, $meliClient);
    
    expect(fn() => $service->getToken('client-id', 'client-secret', 'auth-code', 'https://example.com/callback'))
        ->toThrow(ApiException::class);
});
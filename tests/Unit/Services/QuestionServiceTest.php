<?php

use Zdearo\Meli\Services\QuestionService;

// Mock functions for testing
if (! function_exists('app')) {
    function app(?string $abstract = null)
    {
        if ($abstract === 'meli.client') {
            return new \Zdearo\Meli\Support\MeliApiClient;
        }

        return null;
    }
}

if (! function_exists('config')) {
    function config(string $key, $default = null)
    {
        $configs = [
            'meli.client_id' => 'test-client-id',
            'meli.client_secret' => 'test-client-secret',
            'meli.redirect_uri' => 'https://example.com/callback',
            'meli.auth_domain' => 'mercadolibre.com.br',
            'meli.base_url' => 'https://api.mercadolibre.com/',
            'meli.api_token' => 'test-api-token',
        ];

        return $configs[$key] ?? $default;
    }
}

test('can create question service instance', function () {
    $service = new QuestionService;

    expect($service)->toBeInstanceOf(QuestionService::class);
});

test('service methods exist and are callable', function () {
    $service = new QuestionService;

    expect(method_exists($service, 'search'))->toBeTrue();
    expect(method_exists($service, 'getBySeller'))->toBeTrue();
    expect(method_exists($service, 'getByItem'))->toBeTrue();
    expect(method_exists($service, 'getByUser'))->toBeTrue();
    expect(method_exists($service, 'get'))->toBeTrue();
    expect(method_exists($service, 'create'))->toBeTrue();
    expect(method_exists($service, 'answer'))->toBeTrue();
    expect(method_exists($service, 'delete'))->toBeTrue();
    expect(method_exists($service, 'getUnansweredBySeller'))->toBeTrue();
    expect(method_exists($service, 'getResponseTime'))->toBeTrue();
});

<?php

use Zdearo\Meli\Services\QuestionService;

// Mock app function for Laravel service container
if (! function_exists('app')) {
    function app(?string $abstract = null)
    {
        if ($abstract === 'meli.client') {
            return new \Zdearo\Meli\Support\MeliApiClient;
        }

        return null;
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
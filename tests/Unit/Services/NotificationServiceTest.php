<?php

use Zdearo\Meli\Services\NotificationService;

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

test('can create notification service instance', function () {
    $service = new NotificationService;

    expect($service)->toBeInstanceOf(NotificationService::class);
});

test('service methods exist and are callable', function () {
    $service = new NotificationService;

    expect(method_exists($service, 'getMissedFeeds'))->toBeTrue();
    expect(method_exists($service, 'getMissedFeedsByTopic'))->toBeTrue();
    expect(method_exists($service, 'getMissedFeedsPaginated'))->toBeTrue();
    expect(method_exists($service, 'getResourceFromNotification'))->toBeTrue();
    expect(method_exists($service, 'processNotification'))->toBeTrue();
    expect(method_exists($service, 'validateNotification'))->toBeTrue();
    expect(method_exists($service, 'getNotificationTopic'))->toBeTrue();
    expect(method_exists($service, 'isTopicNotification'))->toBeTrue();
    expect(method_exists($service, 'getOrdersNotifications'))->toBeTrue();
});

test('can validate notification format', function () {
    $service = new NotificationService;

    $validNotification = [
        'resource' => '/orders/123456',
        'user_id' => 123456789,
        'topic' => 'orders_v2',
        'application_id' => 987654321,
    ];

    $invalidNotification = [
        'resource' => '/orders/123456',
        // missing required fields
    ];

    expect($service->validateNotification($validNotification))->toBeTrue();
    expect($service->validateNotification($invalidNotification))->toBeFalse();
});

test('can extract notification topic', function () {
    $service = new NotificationService;

    $notification = [
        'resource' => '/orders/123456',
        'user_id' => 123456789,
        'topic' => 'orders_v2',
        'application_id' => 987654321,
    ];

    expect($service->getNotificationTopic($notification))->toBe('orders_v2');
});

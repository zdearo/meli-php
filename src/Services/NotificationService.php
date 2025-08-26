<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for managing notifications in the Mercado Libre API.
 */
class NotificationService
{
    /**
     * Get missed notifications for an application.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters (topic, offset, limit)
     * @return array<string, mixed> The missed notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getMissedFeeds(int $appId, array $filters = []): array
    {
        $filters['app_id'] = $appId;
        
        return ApiRequest::get('missed_feeds')
            ->withQuery($filters)
            ->send()
            ->json();
    }

    /**
     * Get missed notifications by topic.
     *
     * @param  int  $appId  The application ID
     * @param  string  $topic  The notification topic
     * @param  array<string, mixed>  $filters  Optional filters (offset, limit)
     * @return array<string, mixed> The filtered notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getMissedFeedsByTopic(int $appId, string $topic, array $filters = []): array
    {
        $filters['topic'] = $topic;
        return $this->getMissedFeeds($appId, $filters);
    }

    /**
     * Get missed notifications with pagination.
     *
     * @param  int  $appId  The application ID
     * @param  int  $limit  Number of results per page (default: 10, max: 100)
     * @param  int  $offset  Results offset (default: 0)
     * @param  string|null  $topic  Optional topic filter
     * @return array<string, mixed> The paginated notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getMissedFeedsPaginated(int $appId, int $limit = 10, int $offset = 0, ?string $topic = null): array
    {
        $filters = [
            'limit' => $limit,
            'offset' => $offset,
        ];

        if ($topic) {
            $filters['topic'] = $topic;
        }

        return $this->getMissedFeeds($appId, $filters);
    }

    /**
     * Get the complete resource data based on a notification.
     *
     * @param  array<string, mixed>  $notification  The notification data
     * @return array<string, mixed> The resource data
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getResourceFromNotification(array $notification): array
    {
        if (!isset($notification['resource'])) {
            throw new \InvalidArgumentException('Notification must contain a resource field');
        }

        $resource = ltrim($notification['resource'], '/');
        
        return ApiRequest::get($resource)
            ->send()
            ->json();
    }

    /**
     * Process a notification and return the resource data.
     *
     * @param  array<string, mixed>  $notification  The notification data
     * @return array<string, mixed> An array with notification details and resource data
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function processNotification(array $notification): array
    {
        $resourceData = $this->getResourceFromNotification($notification);
        
        return [
            'notification' => $notification,
            'resource_data' => $resourceData,
        ];
    }

    /**
     * Validate notification format.
     *
     * @param  array<string, mixed>  $notification  The notification data
     * @return bool True if valid
     */
    public function validateNotification(array $notification): bool
    {
        $required = ['resource', 'user_id', 'topic', 'application_id'];
        
        foreach ($required as $field) {
            if (!isset($notification[$field])) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get notification topic from notification data.
     *
     * @param  array<string, mixed>  $notification  The notification data
     * @return string|null The topic or null if not found
     */
    public function getNotificationTopic(array $notification): ?string
    {
        return $notification['topic'] ?? null;
    }

    /**
     * Get notification actions (for subtopic structure).
     *
     * @param  array<string, mixed>  $notification  The notification data
     * @return array<string>|null The actions or null if not found
     */
    public function getNotificationActions(array $notification): ?array
    {
        return $notification['actions'] ?? null;
    }

    /**
     * Check if notification is from a specific topic.
     *
     * @param  array<string, mixed>  $notification  The notification data
     * @param  string  $topic  The topic to check
     * @return bool True if matches
     */
    public function isTopicNotification(array $notification, string $topic): bool
    {
        return $this->getNotificationTopic($notification) === $topic;
    }

    /**
     * Check if notification contains specific action (for subtopic structure).
     *
     * @param  array<string, mixed>  $notification  The notification data
     * @param  string  $action  The action to check
     * @return bool True if contains the action
     */
    public function hasNotificationAction(array $notification, string $action): bool
    {
        $actions = $this->getNotificationActions($notification);
        return $actions ? in_array($action, $actions) : false;
    }

    /**
     * Get orders notifications.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters
     * @return array<string, mixed> The orders notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getOrdersNotifications(int $appId, array $filters = []): array
    {
        return $this->getMissedFeedsByTopic($appId, 'orders_v2', $filters);
    }

    /**
     * Get items notifications.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters
     * @return array<string, mixed> The items notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getItemsNotifications(int $appId, array $filters = []): array
    {
        return $this->getMissedFeedsByTopic($appId, 'items', $filters);
    }

    /**
     * Get questions notifications.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters
     * @return array<string, mixed> The questions notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getQuestionsNotifications(int $appId, array $filters = []): array
    {
        return $this->getMissedFeedsByTopic($appId, 'questions', $filters);
    }

    /**
     * Get payments notifications.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters
     * @return array<string, mixed> The payments notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getPaymentsNotifications(int $appId, array $filters = []): array
    {
        return $this->getMissedFeedsByTopic($appId, 'payments', $filters);
    }

    /**
     * Get shipments notifications.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters
     * @return array<string, mixed> The shipments notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getShipmentsNotifications(int $appId, array $filters = []): array
    {
        return $this->getMissedFeedsByTopic($appId, 'shipments', $filters);
    }

    /**
     * Get messages notifications.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters
     * @return array<string, mixed> The messages notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getMessagesNotifications(int $appId, array $filters = []): array
    {
        return $this->getMissedFeedsByTopic($appId, 'messages', $filters);
    }

    /**
     * Get claims notifications.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters
     * @return array<string, mixed> The claims notifications
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getClaimsNotifications(int $appId, array $filters = []): array
    {
        return $this->getMissedFeedsByTopic($appId, 'post_purchase', $filters);
    }
}
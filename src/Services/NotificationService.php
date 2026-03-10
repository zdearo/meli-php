<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\DTO\SearchResult;
use Zdearo\Meli\Support\ApiRequest;
use Zdearo\Meli\Support\LazyPaginator;

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
     * @return SearchResult The missed notifications
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getMissedFeeds(int $appId, array $filters = []): SearchResult
    {
        $filters['app_id'] = $appId;

        $data = ApiRequest::get('missed_feeds')
            ->withQuery($filters)
            ->send()
            ->json();

        return new SearchResult(
            results: $data['results'] ?? $data['messages'] ?? [],
            total: $data['paging']['total'] ?? 0,
            offset: $data['paging']['offset'] ?? 0,
            limit: $data['paging']['limit'] ?? 10,
        );
    }

    /**
     * Lazily iterate over all missed notifications.
     *
     * @param  int  $appId  The application ID
     * @param  array<string, mixed>  $filters  Optional filters
     * @param  int  $limit  Results per page
     * @return LazyPaginator Paginator yielding notification items
     */
    public function getMissedFeedsAll(int $appId, array $filters = [], int $limit = 100): LazyPaginator
    {
        return new LazyPaginator(
            fetcher: function (int $offset, int $limit) use ($appId, $filters) {
                $filters['limit'] = $limit;
                $filters['offset'] = $offset;

                return $this->getMissedFeeds($appId, $filters);
            },
            limit: $limit,
        );
    }

    /**
     * Get missed notifications by topic.
     */
    public function getMissedFeedsByTopic(int $appId, string $topic, array $filters = []): SearchResult
    {
        $filters['topic'] = $topic;

        return $this->getMissedFeeds($appId, $filters);
    }

    /**
     * Get missed notifications with pagination.
     */
    public function getMissedFeedsPaginated(int $appId, int $limit = 10, int $offset = 0, ?string $topic = null): SearchResult
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
     * @return Response The resource data
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getResourceFromNotification(array $notification): Response
    {
        if (! isset($notification['resource'])) {
            throw new \InvalidArgumentException('Notification must contain a resource field');
        }

        $resource = ltrim($notification['resource'], '/');

        return ApiRequest::get($resource)
            ->send();
    }

    /**
     * Process a notification and return the resource data.
     *
     * @param  array<string, mixed>  $notification  The notification data
     * @return array{notification: array, resource_data: Response} Notification details and resource data
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
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
     */
    public function validateNotification(array $notification): bool
    {
        $required = ['resource', 'user_id', 'topic', 'application_id'];

        foreach ($required as $field) {
            if (! isset($notification[$field])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get notification topic from notification data.
     */
    public function getNotificationTopic(array $notification): ?string
    {
        return $notification['topic'] ?? null;
    }

    /**
     * Get notification actions (for subtopic structure).
     */
    public function getNotificationActions(array $notification): ?array
    {
        return $notification['actions'] ?? null;
    }

    /**
     * Check if notification is from a specific topic.
     */
    public function isTopicNotification(array $notification, string $topic): bool
    {
        return $this->getNotificationTopic($notification) === $topic;
    }

    /**
     * Check if notification contains specific action.
     */
    public function hasNotificationAction(array $notification, string $action): bool
    {
        $actions = $this->getNotificationActions($notification);

        return $actions ? in_array($action, $actions) : false;
    }

    public function getOrdersNotifications(int $appId, array $filters = []): SearchResult
    {
        return $this->getMissedFeedsByTopic($appId, 'orders_v2', $filters);
    }

    public function getItemsNotifications(int $appId, array $filters = []): SearchResult
    {
        return $this->getMissedFeedsByTopic($appId, 'items', $filters);
    }

    public function getQuestionsNotifications(int $appId, array $filters = []): SearchResult
    {
        return $this->getMissedFeedsByTopic($appId, 'questions', $filters);
    }

    public function getPaymentsNotifications(int $appId, array $filters = []): SearchResult
    {
        return $this->getMissedFeedsByTopic($appId, 'payments', $filters);
    }

    public function getShipmentsNotifications(int $appId, array $filters = []): SearchResult
    {
        return $this->getMissedFeedsByTopic($appId, 'shipments', $filters);
    }

    public function getMessagesNotifications(int $appId, array $filters = []): SearchResult
    {
        return $this->getMissedFeedsByTopic($appId, 'messages', $filters);
    }

    public function getClaimsNotifications(int $appId, array $filters = []): SearchResult
    {
        return $this->getMissedFeedsByTopic($appId, 'post_purchase', $filters);
    }
}

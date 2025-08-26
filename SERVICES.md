# Services Documentation

This document provides detailed information about all available services in the Mercado Libre PHP SDK.

## Table of Contents

- [AuthService](#authservice)
- [ProductService](#productservice)
- [SearchItemService](#searchitemservice)
- [UserService](#userservice)
- [CategoryService](#categoryservice)
- [OrderService](#orderservice)
- [QuestionService](#questionservice)
- [PaymentService](#paymentservice)
- [NotificationService](#notificationservice)
- [VisitsService](#visitsservice)

---

## AuthService

Handles OAuth authentication with the Mercado Libre API.

### Methods

#### `getToken(string $code): Response`
Exchange an authorization code for an access token.

```php
$response = Meli::auth()->getToken($authorizationCode);
$tokenData = $response->json();
```

#### `refreshToken(string $refreshToken): Response`
Refresh an access token using a refresh token.

```php
$response = Meli::auth()->refreshToken($refreshToken);
$newTokenData = $response->json();
```

---

## ProductService

Manages product operations including CRUD operations and status management.

### Methods

#### `create(array $productData): array`
Create a new product listing.

```php
$product = Meli::products()->create([
    'title' => 'Amazing Product',
    'category_id' => 'MLB1055',
    'price' => 99.99,
    'currency_id' => 'BRL',
    'available_quantity' => 10,
    'condition' => 'new'
]);
```

#### `get(string $itemId): array`
Get product details by item ID.

```php
$product = Meli::products()->get('MLB123456789');
```

#### `update(string $itemId, array $updateData): array`
Update product information.

```php
$product = Meli::products()->update('MLB123456789', [
    'price' => 89.99,
    'available_quantity' => 5
]);
```

#### `changeStatus(string $itemId, string $status): array`
Change product status (active, paused, closed).

```php
$product = Meli::products()->changeStatus('MLB123456789', 'paused');
```

---

## SearchItemService

Provides search functionality for products and items.

### Methods

#### `byQuery(string $query, array $filters = []): array`
Search products by query string.

```php
$results = Meli::searchItem()->byQuery('smartphone samsung', [
    'limit' => 20,
    'offset' => 0,
    'sort' => 'price_asc'
]);
```

#### `byCategory(string $categoryId, array $filters = []): array`
Search products within a specific category.

```php
$results = Meli::searchItem()->byCategory('MLB1055');
```

#### `bySeller(int $sellerId, array $filters = []): array`
Search products from a specific seller.

```php
$results = Meli::searchItem()->bySeller(123456789);
```

#### `byUserItems(int $userId, array $filters = []): array`
Get items from a specific user.

```php
$items = Meli::searchItem()->byUserItems(123456789);
```

#### `multiGetItems(array $itemIds): array`
Get multiple items by their IDs in a single request.

```php
$items = Meli::searchItem()->multiGetItems(['MLB123', 'MLB456', 'MLB789']);
```

---

## UserService

Manages user information and account-related operations.

### Methods

#### `get(int $userId): array`
Get user information by user ID.

```php
$user = Meli::users()->get(123456789);
```

#### `me(): array`
Get authenticated user information.

```php
$currentUser = Meli::users()->me();
```

#### `update(int $userId, array $userData): array`
Update user information.

```php
$user = Meli::users()->update(123456789, [
    'first_name' => 'John',
    'last_name' => 'Doe'
]);
```

#### `getAddresses(int $userId): array`
Get user addresses.

```php
$addresses = Meli::users()->getAddresses(123456789);
```

#### `getAcceptedPaymentMethods(int $userId): array`
Get payment methods accepted by the seller.

```php
$paymentMethods = Meli::users()->getAcceptedPaymentMethods(123456789);
```

#### `getBrands(int $userId): array`
Get user's brands (for official stores).

```php
$brands = Meli::users()->getBrands(123456789);
```

#### `getAvailableListingTypes(int $userId, string $categoryId = null): array`
Get available listing types for the user.

```php
$listingTypes = Meli::users()->getAvailableListingTypes(123456789, 'MLB1055');
```

---

## CategoryService

Handles categories, sites, and marketplace structure information.

### Methods

#### `getSites(): array`
Get all available Mercado Libre sites.

```php
$sites = Meli::categories()->getSites();
```

#### `getCategories(string $siteId): array`
Get all categories for a specific site.

```php
$categories = Meli::categories()->getCategories('MLB');
```

#### `get(string $categoryId): array`
Get category details by ID.

```php
$category = Meli::categories()->get('MLB1055');
```

#### `getAttributes(string $categoryId): array`
Get category attributes and requirements.

```php
$attributes = Meli::categories()->getAttributes('MLB1055');
```

#### `getListingPrices(string $siteId, float $price): array`
Get listing prices and fees for a site.

```php
$prices = Meli::categories()->getListingPrices('MLB', 99.99);
```

#### `predictCategory(string $siteId, string $query, int $limit = null): array`
Predict category based on title or attributes.

```php
$predictions = Meli::categories()->predictCategory('MLB', 'smartphone samsung', 5);
```

#### `getDomainTechnicalSpecs(string $domainId): array`
Get technical specifications for a domain.

```php
$specs = Meli::categories()->getDomainTechnicalSpecs('MLB-CELLPHONES');
```

---

## OrderService

Manages orders and sales information.

### Methods

#### `get(int $orderId): array`
Get order details by ID.

```php
$order = Meli::orders()->get(2000003508419013);
```

#### `search(array $filters = []): array`
Search orders with filters.

```php
$orders = Meli::orders()->search([
    'seller' => 123456789,
    'order.status' => 'paid',
    'limit' => 20
]);
```

#### `getBySeller(int $sellerId, array $filters = []): array`
Get orders by seller ID.

```php
$orders = Meli::orders()->getBySeller(123456789);
```

#### `getByStatus(string $status, array $filters = []): array`
Get orders by status.

```php
$paidOrders = Meli::orders()->getByStatus('paid');
```

#### `getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): array`
Get orders within a date range.

```php
$orders = Meli::orders()->getByDateRange(
    '2024-01-01T00:00:00.000Z',
    '2024-12-31T23:59:59.999Z',
    'created'
);
```

#### `getByTags(string|array $tags, array $filters = []): array`
Get orders with specific tags.

```php
$deliveredOrders = Meli::orders()->getByTags('delivered');
$orders = Meli::orders()->getByTags(['paid', 'delivered']);
```

#### `getMercadoShopsOrders(array $filters = []): array`
Get Mercado Shops orders.

```php
$mshopsOrders = Meli::orders()->getMercadoShopsOrders();
```

#### `getProductInfo(int $orderId): array`
Get product information for an order.

```php
$productInfo = Meli::orders()->getProductInfo(2000003508419013);
```

#### `getDiscounts(int $orderId): array`
Get discounts applied to an order.

```php
$discounts = Meli::orders()->getDiscounts(2000003508419013);
```

---

## QuestionService

Manages questions and answers for products.

### Methods

#### `search(array $filters = []): array`
Search questions with filters.

```php
$questions = Meli::questions()->search([
    'seller_id' => 123456789,
    'status' => 'UNANSWERED',
    'limit' => 50
]);
```

#### `getBySeller(int $sellerId, array $filters = []): array`
Get questions received by a seller.

```php
$questions = Meli::questions()->getBySeller(123456789);
```

#### `getByItem(string $itemId, array $filters = []): array`
Get questions for a specific item.

```php
$questions = Meli::questions()->getByItem('MLB123456789');
```

#### `get(int $questionId): array`
Get question details by ID.

```php
$question = Meli::questions()->get(11436370259);
```

#### `create(string $itemId, string $text): array`
Create a new question for an item.

```php
$question = Meli::questions()->create('MLB123456789', 'Do you have this in red?');
```

#### `answer(int $questionId, string $text): array`
Answer a question.

```php
$answer = Meli::questions()->answer(11436370259, 'Yes, we have it in red!');
```

#### `delete(int $questionId): Response`
Delete a question.

```php
Meli::questions()->delete(11436370259);
```

#### `getUnansweredBySeller(int $sellerId, array $filters = []): array`
Get unanswered questions for a seller.

```php
$unanswered = Meli::questions()->getUnansweredBySeller(123456789);
```

#### `getResponseTime(int $userId): array`
Get response time metrics for a user.

```php
$metrics = Meli::questions()->getResponseTime(123456789);
```

---

## PaymentService

Handles payment information and transaction details.

### Methods

#### `get(int $paymentId): array`
Get payment details by ID.

```php
$payment = Meli::payments()->get(21463688923);
```

#### `search(array $filters = []): array`
Search payments with filters.

```php
$payments = Meli::payments()->search([
    'collector_id' => 123456789,
    'status' => 'approved',
    'limit' => 20
]);
```

#### `getByOrder(int $orderId): array`
Get payments for a specific order.

```php
$payments = Meli::payments()->getByOrder(2000003508419013);
```

#### `getByStatus(string $status, array $filters = []): array`
Get payments by status.

```php
$approvedPayments = Meli::payments()->getByStatus('approved');
```

#### `getByPaymentMethod(string $paymentMethodId, array $filters = []): array`
Get payments by payment method.

```php
$cardPayments = Meli::payments()->getByPaymentMethod('visa');
$accountPayments = Meli::payments()->getByPaymentMethod('account_money');
```

#### `getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): array`
Get payments within a date range.

```php
$payments = Meli::payments()->getByDateRange(
    '2024-01-01T00:00:00.000Z',
    '2024-12-31T23:59:59.999Z',
    'approved'
);
```

#### `getApprovedPayments(array $filters = []): array`
Get approved payments.

```php
$approved = Meli::payments()->getApprovedPayments();
```

#### `getPendingPayments(array $filters = []): array`
Get pending payments.

```php
$pending = Meli::payments()->getPendingPayments();
```

---

## NotificationService

Manages webhook notifications and missed feeds.

### Methods

#### `getMissedFeeds(int $appId, array $filters = []): array`
Get missed notifications for an application.

```php
$missedFeeds = Meli::notifications()->getMissedFeeds(123456789);
```

#### `getMissedFeedsByTopic(int $appId, string $topic, array $filters = []): array`
Get missed notifications by topic.

```php
$orderNotifications = Meli::notifications()->getMissedFeedsByTopic(123456789, 'orders_v2');
```

#### `getMissedFeedsPaginated(int $appId, int $limit = 10, int $offset = 0, string $topic = null): array`
Get missed notifications with pagination.

```php
$feeds = Meli::notifications()->getMissedFeedsPaginated(123456789, 20, 0, 'orders_v2');
```

#### `getResourceFromNotification(array $notification): array`
Get complete resource data from a notification.

```php
$notification = [
    'resource' => '/orders/2000003508419013',
    'user_id' => 123456789,
    'topic' => 'orders_v2',
    'application_id' => 987654321
];

$resourceData = Meli::notifications()->getResourceFromNotification($notification);
```

#### `processNotification(array $notification): array`
Process a notification and return both notification and resource data.

```php
$result = Meli::notifications()->processNotification($notification);
// Returns: ['notification' => ..., 'resource_data' => ...]
```

#### `validateNotification(array $notification): bool`
Validate notification format.

```php
$isValid = Meli::notifications()->validateNotification($notification);
```

#### `getOrdersNotifications(int $appId, array $filters = []): array`
Get order-specific notifications.

```php
$orderNotifications = Meli::notifications()->getOrdersNotifications(123456789);
```

---

## VisitsService

Provides analytics and visit tracking functionality.

### Methods

#### `totalByUser(int $userId, string $dateFrom, string $dateTo): array`
Get total visits for a user in a date range.

```php
$visits = Meli::visits()->totalByUser(123456789, '2024-01-01', '2024-12-31');
```

#### `totalByItem(string $itemId): array`
Get total visits for a specific item.

```php
$visits = Meli::visits()->totalByItem('MLB123456789');
```

#### `visitsByUserTimeWindow(int $userId, int $period, string $unit): array`
Get visits for a user in a specific time window.

```php
// Last 30 days
$visits = Meli::visits()->visitsByUserTimeWindow(123456789, 30, 'day');

// Last 12 months
$visits = Meli::visits()->visitsByUserTimeWindow(123456789, 12, 'month');
```

---

## Common Patterns

### Error Handling

All services throw `Illuminate\Http\Client\RequestException` for API errors:

```php
use Illuminate\Http\Client\RequestException;

try {
    $product = Meli::products()->get('MLB123456789');
} catch (RequestException $e) {
    // Handle API error
    $statusCode = $e->response->status();
    $errorBody = $e->response->body();
}
```

### Pagination

Many services support pagination with `limit` and `offset` parameters:

```php
$orders = Meli::orders()->search([
    'seller' => 123456789,
    'limit' => 50,
    'offset' => 100
]);
```

### Filtering

Services accept flexible filter arrays:

```php
$orders = Meli::orders()->search([
    'seller' => 123456789,
    'order.status' => 'paid',
    'order.date_created.from' => '2024-01-01T00:00:00.000Z',
    'order.date_created.to' => '2024-12-31T23:59:59.999Z',
    'tags' => 'delivered',
    'sort' => 'date_desc',
    'limit' => 20
]);
```

### Date Formats

The API expects ISO 8601 format for dates:

```php
$dateFrom = '2024-01-01T00:00:00.000Z';
$dateTo = '2024-12-31T23:59:59.999Z';
```
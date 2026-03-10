# Services Documentation

Detailed documentation for all services in the Mercado Libre PHP SDK.

All services are accessed via the `Meli` facade. Methods that return a single resource return a **DTO**. Search/list methods return a **SearchResult** DTO. Auxiliary endpoints return a raw **Response**.

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
- [DocumentationService](#documentationservice)
- [Common Patterns](#common-patterns)

---

## AuthService

Handles OAuth 2.0 authentication with the Mercado Libre API.

**Accessor:** `Meli::auth()`

### Methods

#### `getToken(string $code): AuthToken`

Exchange an authorization code for an access token.

```php
$token = Meli::auth()->getToken($code);
$token->accessToken;    // string
$token->refreshToken;   // ?string
$token->expiresIn;      // int (seconds)
$token->userId;         // ?int
$token->scope;          // ?string
$token->extra;          // array (unmapped fields)
```

#### `refreshToken(string $refreshToken): AuthToken`

Refresh an access token using a refresh token.

```php
$token = Meli::auth()->refreshToken($refreshToken);
```

---

## ProductService

Manages product (item) CRUD operations.

**Accessor:** `Meli::products()`

### Methods

#### `create(array $productData): Product`

Create a new product listing.

```php
$product = Meli::products()->create([
    'title' => 'Amazing Product',
    'category_id' => 'MLB1055',
    'price' => 99.99,
    'currency_id' => 'BRL',
    'available_quantity' => 10,
    'condition' => 'new',
]);
$product->id;    // "MLB123456789"
```

#### `get(string $itemId): Product`

Get product details by item ID.

```php
$product = Meli::products()->get('MLB123456789');
$product->title;            // string
$product->price;            // ?float
$product->currencyId;       // ?string
$product->categoryId;       // ?string
$product->status;           // ?string
$product->availableQuantity; // ?int
$product->soldQuantity;     // ?int
$product->permalink;        // ?string
$product->thumbnail;        // ?string
$product->pictures;         // array
$product->attributes;       // array
$product->variations;       // array
$product->shipping;         // array
$product->extra;            // array
```

#### `update(string $itemId, array $updateData): Product`

Update product information.

```php
$product = Meli::products()->update('MLB123456789', [
    'price' => 89.99,
    'available_quantity' => 5,
]);
```

#### `changeStatus(string $itemId, string $status): Product`

Change product status (`active`, `paused`, `closed`).

```php
$product = Meli::products()->changeStatus('MLB123456789', 'paused');
```

---

## SearchItemService

Product search and discovery.

**Accessor:** `Meli::searchItem()`

### Methods

#### `byQuery(string $value, int $offset = 0): SearchResult`

Search items by query string.

```php
$results = Meli::searchItem()->byQuery('smartphone samsung');
$results->results;  // array (raw search items)
$results->total;    // int
$results->offset;   // int
$results->limit;    // int
```

#### `byQueryAll(string $value, int $limit = 50): LazyPaginator`

Lazily iterate over all items matching a query.

```php
Meli::searchItem()->byQueryAll('smartphone')->cursor()->each(fn ($item) => ...);
```

#### `byCategory(string $categoryId): SearchResult`

Search items within a specific category.

```php
$results = Meli::searchItem()->byCategory('MLB1055');
```

#### `byNickname(string $nickname): SearchResult`

Search items by seller nickname.

```php
$results = Meli::searchItem()->byNickname('SELLER_NICK');
```

#### `bySeller(int $sellerId, ?string $categoryId = null): SearchResult`

Search items from a specific seller, optionally filtered by category.

```php
$results = Meli::searchItem()->bySeller(123456789);
$results = Meli::searchItem()->bySeller(123456789, 'MLB1055');
```

#### `byUserItems(int $userId, bool $scan = false): Response`

Get items listed by a user. Pass `$scan = true` for scan/scroll pagination.

```php
$response = Meli::searchItem()->byUserItems(123456789);
```

#### `multiGetItems(array $itemIds, array $attributes = []): array<Product>`

Get multiple items by IDs. Returns an array of `Product` DTOs.

```php
$products = Meli::searchItem()->multiGetItems(['MLB123', 'MLB456']);
foreach ($products as $product) {
    echo $product->title;
}
```

#### `multiGetUsers(array $userIds): array<User>`

Get multiple users by IDs. Returns an array of `User` DTOs.

```php
$users = Meli::searchItem()->multiGetUsers([123456, 789012]);
```

---

## UserService

User information and account management.

**Accessor:** `Meli::users()`

### Methods

#### `get(int $userId): User`

Get user information by ID.

```php
$user = Meli::users()->get(123456789);
$user->nickname;           // ?string
$user->firstName;          // ?string
$user->lastName;           // ?string
$user->email;              // ?string
$user->countryId;          // ?string
$user->sellerReputation;   // array
$user->buyerReputation;    // array
$user->extra;              // array
```

#### `me(): User`

Get authenticated user information.

```php
$me = Meli::users()->me();
```

#### `update(int $userId, array $userData): User`

Update user information.

```php
$user = Meli::users()->update(123456789, ['first_name' => 'John']);
```

#### `getAddresses(int $userId): Response`

Get user addresses.

```php
$response = Meli::users()->getAddresses(123456789);
$addresses = $response->json();
```

#### `getAcceptedPaymentMethods(int $userId): Response`

Get accepted payment methods.

#### `getBrands(int $userId): Response`

Get user's brands.

#### `getAvailableListingTypes(int $userId, ?string $categoryId = null): Response`

Get available listing types.

#### `getAvailableListingType(int $userId, string $listingTypeId, ?string $categoryId = null): Response`

Get a specific listing type availability.

#### `getClassifiedsPromotionPacks(int $userId): Response`

Get classifieds promotion packs.

#### `createClassifiedsPromotionPack(int $userId, array $packData): Response`

Create a promotion pack.

#### `checkListingAvailability(int $userId, string $listingType, string $categoryId): Response`

Check listing availability for a type and category.

#### `revokeApplicationPermission(int $userId, int $applicationId): Response`

Revoke an application's permission.

---

## CategoryService

Categories, attributes, and marketplace structure.

**Accessor:** `Meli::categories()`

### Methods

#### `get(string $categoryId): Category`

Get category details by ID. Returns a `Category` DTO.

```php
$category = Meli::categories()->get('MLB1055');
$category->id;                  // string
$category->name;                // ?string
$category->pathFromRoot;        // array
$category->childrenCategories;  // array
$category->settings;            // array
$category->extra;               // array
```

#### `getSites(): Response`

Get all available Mercado Libre sites.

#### `getCategories(string $siteId): Response`

Get all categories for a site.

```php
$response = Meli::categories()->getCategories('MLB');
```

#### `getAttributes(string $categoryId): Response`

Get category attributes and requirements.

#### `getListingExposures(string $siteId): Response`

Get listing types for a site.

#### `getListingPrices(string $siteId, float $price): Response`

Get listing prices and fees.

#### `predictCategory(string $siteId, string $query, ?int $limit = null): Response`

Predict category based on title or attributes.

```php
$response = Meli::categories()->predictCategory('MLB', 'iPhone 15', 5);
```

#### `getClassifiedsPromotionPacks(string $categoryId): Response`

Get promotion packs for a category.

#### `getDomainTechnicalSpecs(string $domainId): Response`

Get technical specifications for a domain.

---

## OrderService

Order management and sales tracking.

**Accessor:** `Meli::orders()`

### Methods

#### `get(int $orderId): Order`

Get order details by ID. Returns an `Order` DTO.

```php
$order = Meli::orders()->get(2000003508419013);
$order->id;              // int
$order->status;          // ?string
$order->statusDetail;    // ?string
$order->totalAmount;     // ?float
$order->currencyId;      // ?string
$order->dateCreated;     // ?string
$order->dateClosed;      // ?string
$order->buyer;           // array
$order->seller;          // array
$order->orderItems;      // array<OrderItem>
$order->payments;        // array
$order->shipping;        // array
$order->tags;            // array
$order->extra;           // array
```

Each `OrderItem` has: `$item` (array), `$quantity` (int), `$unitPrice` (float), `$currencyId` (?string), `$extra` (array).

#### `search(array $filters = []): SearchResult`

Search orders. Returns `SearchResult` containing `Order` DTOs.

```php
$result = Meli::orders()->search([
    'seller' => 123456789,
    'order.status' => 'paid',
    'limit' => 20,
]);
$result->results;  // array<Order>
$result->total;    // int
```

#### `searchAll(array $filters = [], int $limit = 50): LazyPaginator`

Lazily iterate over all matching orders.

```php
Meli::orders()->searchAll(['seller' => $sellerId])->cursor()->each(fn (Order $o) => ...);
$all = Meli::orders()->searchAll(['seller' => $sellerId])->all(); // Collection
```

#### `getBySeller(int $sellerId, array $filters = []): SearchResult`

Get orders by seller ID.

#### `getByBuyer(int $buyerId, array $filters = []): SearchResult`

Get orders by buyer ID.

#### `getByStatus(string $status, array $filters = []): SearchResult`

Get orders by status.

#### `getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): SearchResult`

Get orders within a date range.

```php
$orders = Meli::orders()->getByDateRange(
    '2024-01-01T00:00:00.000Z',
    '2024-12-31T23:59:59.999Z',
);
```

#### `getByTags(string|array $tags, array $filters = []): SearchResult`

Get orders with specific tags.

#### `getExcludingTags(string|array $tags, array $filters = []): SearchResult`

Get orders excluding specific tags.

#### `getMercadoShopsOrders(array $filters = []): SearchResult`

Get Mercado Shops orders.

#### `getProductInfo(int $orderId): Response`

Get product information for an order.

#### `getDiscounts(int $orderId): Response`

Get discounts applied to an order.

#### `searchPaginated(array $filters = [], int $limit = 50, int $offset = 0, string $sort = 'date_asc'): SearchResult`

Search with explicit pagination and sorting.

#### Convenience Methods

| Method | Filter |
|---|---|
| `getPaidOrders($filters)` | `status = paid` |
| `getCancelledOrders($filters)` | `status = cancelled` |
| `getDeliveredOrders($filters)` | `tags = delivered` |
| `getFraudRiskOrders($filters)` | `tags = fraud_risk_detected` |

---

## QuestionService

Q&A system for products.

**Accessor:** `Meli::questions()`

### Methods

#### `get(int $questionId): Question`

Get question details by ID. Returns a `Question` DTO.

```php
$question = Meli::questions()->get(11436370259);
$question->id;          // int
$question->itemId;      // ?string
$question->sellerId;    // ?int
$question->status;      // ?string (ANSWERED, UNANSWERED, BANNED, etc.)
$question->text;        // ?string
$question->dateCreated; // ?string
$question->from;        // array (buyer info)
$question->answer;      // ?array (answer data)
$question->extra;       // array
```

#### `search(array $filters = []): SearchResult`

Search questions. Returns `SearchResult` with `Question` DTOs.

```php
$result = Meli::questions()->search(['seller_id' => 123456789, 'status' => 'UNANSWERED']);
```

#### `searchAll(array $filters = [], int $limit = 50): LazyPaginator`

Lazily iterate over all matching questions.

```php
Meli::questions()->searchAll(['seller_id' => $id])->cursor()->each(fn (Question $q) => ...);
```

#### `create(string $itemId, string $text): Question`

Create a new question (max 2000 characters).

```php
$question = Meli::questions()->create('MLB123456789', 'Do you have this in red?');
```

#### `answer(int $questionId, string $text): Response`

Answer a question (max 2000 characters).

```php
Meli::questions()->answer(11436370259, 'Yes, we have it in red!');
```

#### `delete(int $questionId): Response`

Delete a question.

#### `getBySeller(int $sellerId, array $filters = []): SearchResult`

Get questions received by a seller.

#### `getByItem(string $itemId, array $filters = []): SearchResult`

Get questions for a specific item.

#### `getByUser(int $userId, string $itemId, array $filters = []): SearchResult`

Get questions by a user for an item.

#### `getByStatus(string $status, array $filters = []): SearchResult`

Get questions with a specific status.

#### `getUnansweredBySeller(int $sellerId, array $filters = []): SearchResult`

Get unanswered questions for a seller.

#### `getAnsweredBySeller(int $sellerId, array $filters = []): SearchResult`

Get answered questions for a seller.

#### `searchSorted(array $filters = [], array $sortFields = [], string $sortType = 'ASC'): SearchResult`

Search with sorting.

#### `searchPaginated(array $filters = [], int $limit = 50, int $offset = 0): SearchResult`

Search with explicit pagination.

#### `getResponseTime(int $userId): Response`

Get response time metrics.

#### `hideQuestions(array $questionIds): Response`

Hide questions from listing.

#### Convenience Methods

| Method | Filter |
|---|---|
| `getBannedQuestions($filters)` | `status = BANNED` |
| `getQuestionsUnderReview($filters)` | `status = UNDER_REVIEW` |
| `getClosedUnansweredQuestions($filters)` | `status = CLOSED_UNANSWERED` |

---

## PaymentService

Payment information and transaction details.

**Accessor:** `Meli::payments()`

### Methods

#### `get(int $paymentId): Payment`

Get payment details by ID. Returns a `Payment` DTO.

```php
$payment = Meli::payments()->get(21463688923);
$payment->id;                // int
$payment->orderId;           // ?int
$payment->payerId;           // ?int
$payment->status;            // ?string
$payment->statusDetail;      // ?string
$payment->paymentMethodId;   // ?string
$payment->paymentType;       // ?string
$payment->transactionAmount; // ?float
$payment->totalPaidAmount;   // ?float
$payment->currencyId;        // ?string
$payment->dateCreated;       // ?string
$payment->dateApproved;      // ?string
$payment->installments;      // ?int
$payment->extra;             // array
```

#### `search(array $filters = []): SearchResult`

Search payments. Returns `SearchResult` with `Payment` DTOs.

```php
$result = Meli::payments()->search(['collector_id' => 123456789, 'status' => 'approved']);
```

#### `searchAll(array $filters = [], int $limit = 50): LazyPaginator`

Lazily iterate over all matching payments.

```php
Meli::payments()->searchAll(['collector_id' => $id])->cursor()->each(fn (Payment $p) => ...);
```

#### `getByOrder(int $orderId): SearchResult`

Get payments for an order.

#### `getByExternalReference(string $externalReference): SearchResult`

Get payments by external reference.

#### `getByStatus(string $status, array $filters = []): SearchResult`

Get payments by status.

#### `getByDateRange(string $dateFrom, string $dateTo, string $dateField = 'created', array $filters = []): SearchResult`

Get payments within a date range.

#### `getByPaymentMethod(string $paymentMethodId, array $filters = []): SearchResult`

Get payments by payment method.

#### `getByCollector(int $collectorId, array $filters = []): SearchResult`

Get payments by collector (seller).

#### `getByPayer(int $payerId, array $filters = []): SearchResult`

Get payments by payer (buyer).

#### `searchPaginated(array $filters = [], int $limit = 50, int $offset = 0): SearchResult`

Search with explicit pagination.

#### Convenience Methods

| Method | Filter |
|---|---|
| `getApprovedPayments($filters)` | `status = approved` |
| `getPendingPayments($filters)` | `status = pending` |
| `getRejectedPayments($filters)` | `status = rejected` |
| `getCancelledPayments($filters)` | `status = cancelled` |
| `getRefundedPayments($filters)` | `status = refunded` |
| `getAccountMoneyPayments($filters)` | `payment_method = account_money` |
| `getCreditCardPayments($filters)` | `payment_type = credit_card` |
| `getDebitCardPayments($filters)` | `payment_type = debit_card` |
| `getBankTransferPayments($filters)` | `payment_type = bank_transfer` |
| `getTicketPayments($filters)` | `payment_type = ticket` |
| `getRefundAccountMoneyPayments($filters)` | `tags = refund_account_money` |

---

## NotificationService

Webhook notifications and missed feeds.

**Accessor:** `Meli::notifications()`

### Methods

#### `getMissedFeeds(int $appId, array $filters = []): SearchResult`

Get missed notifications for an application.

```php
$result = Meli::notifications()->getMissedFeeds(123456789);
```

#### `getMissedFeedsAll(int $appId, array $filters = [], int $limit = 100): LazyPaginator`

Lazily iterate over all missed notifications.

```php
Meli::notifications()->getMissedFeedsAll($appId)->cursor()->each(fn ($notif) => ...);
```

#### `getMissedFeedsByTopic(int $appId, string $topic, array $filters = []): SearchResult`

Get missed notifications by topic.

#### `getMissedFeedsPaginated(int $appId, int $limit = 10, int $offset = 0, ?string $topic = null): SearchResult`

Get missed notifications with explicit pagination.

#### `getResourceFromNotification(array $notification): Response`

Fetch the full resource data referenced by a notification.

```php
$resource = Meli::notifications()->getResourceFromNotification([
    'resource' => '/orders/2000003508419013',
    'user_id' => 123456789,
    'topic' => 'orders_v2',
    'application_id' => 987654321,
]);
```

#### `processNotification(array $notification): array`

Process a notification and return both notification and resource data.

```php
$result = Meli::notifications()->processNotification($notification);
// ['notification' => [...], 'resource_data' => Response]
```

#### `validateNotification(array $notification): bool`

Validate notification format (checks for `resource`, `user_id`, `topic`, `application_id`).

#### Helper Methods

| Method | Description |
|---|---|
| `getNotificationTopic($notification)` | Extract topic string |
| `getNotificationActions($notification)` | Extract actions array |
| `isTopicNotification($notification, $topic)` | Check if matches topic |
| `hasNotificationAction($notification, $action)` | Check if contains action |

#### Topic-Specific Methods

| Method | Topic |
|---|---|
| `getOrdersNotifications($appId, $filters)` | `orders_v2` |
| `getItemsNotifications($appId, $filters)` | `items` |
| `getQuestionsNotifications($appId, $filters)` | `questions` |
| `getPaymentsNotifications($appId, $filters)` | `payments` |
| `getShipmentsNotifications($appId, $filters)` | `shipments` |
| `getMessagesNotifications($appId, $filters)` | `messages` |
| `getClaimsNotifications($appId, $filters)` | `post_purchase` |

---

## VisitsService

Analytics and visit tracking.

**Accessor:** `Meli::visits()`

All methods return a raw `Response`.

### Methods

#### `totalByUser(int $userId, string $dateFrom, string $dateTo): Response`

Get total visits for a user's items in a date range.

```php
$response = Meli::visits()->totalByUser(123456789, '2024-01-01', '2024-12-31');
```

#### `totalByItem(string $itemId): Response`

Get total visits for a specific item.

#### `totalByItemsDateRange(array $itemIds, string $dateFrom, string $dateTo): Response`

Get total visits for multiple items in a date range.

#### `visitsByUserTimeWindow(int $userId, int $last, string $unit, ?string $ending = null): Response`

Get visits for a user's items within a time window.

```php
$response = Meli::visits()->visitsByUserTimeWindow(123456789, 30, 'day');
$response = Meli::visits()->visitsByUserTimeWindow(123456789, 12, 'month');
```

#### `visitsByItemTimeWindow(string $itemId, int $last, string $unit, ?string $ending = null): Response`

Get visits for an item within a time window.

---

## DocumentationService

Access Mercado Libre's technical documentation via the MCP server.

**Accessor:** `Meli::documentation()`

### Methods

#### `search(string $query, string $language = 'pt_BR', ?string $siteId = null, ?int $limit = null, ?int $offset = null): array`

Search the technical documentation.

```php
$results = Meli::documentation()->search('items', 'pt_BR');
$results = Meli::documentation()->search('orders', 'es', 'MLA', 10);
```

#### `getPage(string $path, string $language = 'pt_BR', ?string $siteId = null): array`

Get the full content of a documentation page.

```php
$page = Meli::documentation()->getPage('/pt_br/product-search');
```

#### `availableTools(): array`

List all available MCP tools.

---

## Common Patterns

### Error Handling

All services throw typed exceptions extending `MeliException`:

```php
use Zdearo\Meli\Exceptions\MeliNotFoundException;
use Zdearo\Meli\Exceptions\MeliValidationException;
use Zdearo\Meli\Exceptions\MeliException;

try {
    $product = Meli::products()->create($data);
} catch (MeliValidationException $e) {
    // $e->causes — array of validation errors
    // $e->errorCode — "validation_error"
    // $e->context — full response body
} catch (MeliNotFoundException $e) {
    // 404
} catch (MeliException $e) {
    // Catch-all
}
```

### Lazy Pagination

Use `searchAll()` (or `byQueryAll()`, `getMissedFeedsAll()`) for memory-efficient iteration:

```php
// Lazy — pages fetched on demand
Meli::orders()->searchAll(['seller' => $id])->cursor()->each(fn (Order $o) => ...);

// Collect all
$all = Meli::orders()->searchAll(['seller' => $id])->all();
```

### Filtering

Services accept flexible filter arrays:

```php
$result = Meli::orders()->search([
    'seller' => 123456789,
    'order.status' => 'paid',
    'order.date_created.from' => '2024-01-01T00:00:00.000Z',
    'order.date_created.to' => '2024-12-31T23:59:59.999Z',
    'tags' => 'delivered',
    'sort' => 'date_desc',
    'limit' => 20,
]);
```

### Date Formats

The API expects ISO 8601 format for dates:

```
2024-01-01T00:00:00.000Z
```

### DTO Extras

All DTOs have an `$extra` array containing any fields returned by the API that aren't mapped to named properties:

```php
$product = Meli::products()->get('MLB123');
$product->title;               // mapped property
$product->extra['video_id'];   // unmapped field
```

### Multi-Tenancy

```php
// Per-request token
Meli::withToken($token)->products()->get('MLB123');

// Via connection object
Meli::forConnection($connection)->orders()->getBySeller($id);
```

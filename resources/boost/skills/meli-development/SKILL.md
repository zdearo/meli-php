---
name: meli-development
description: Build integrations with the Mercado Libre API using the meli-php Laravel SDK — products, orders, payments, questions, authentication, and more.
---

# Mercado Libre Development

## When to use this skill

Use this skill when working with Mercado Libre API integration: managing products/items, processing orders, handling payments, answering questions, OAuth authentication, webhook notifications, or any feature that uses the `Meli::` facade.

## Namespace Map

```
Zdearo\Meli\Facades\Meli          — Main facade
Zdearo\Meli\DTO\*                  — Data Transfer Objects
Zdearo\Meli\Exceptions\*           — Typed exceptions
Zdearo\Meli\Services\*             — Service classes
Zdearo\Meli\Support\LazyPaginator  — Automatic pagination
Zdearo\Meli\Enums\MarketplaceEnum  — 19 Latin American regions
```

## Authentication (OAuth 2.0)

### Generate Authorization URL

```php
$url = Meli::getAuthUrl($state);
return redirect($url);
```

### Exchange Code for Token

```php
$token = Meli::auth()->getToken($code);
// $token->accessToken, $token->refreshToken, $token->expiresIn, $token->userId
```

### Refresh Token

```php
$token = Meli::auth()->refreshToken($refreshToken);
```

### Multi-tenant Token Resolution

```php
// Per-request token
$user = Meli::withToken($accessToken)->users()->me();

// Via connection object (reads ->access_token or ->getAccessToken())
$orders = Meli::forConnection($meliConnection)->orders()->getBySeller($sellerId);

// Via resolver in config/meli.php
config(['meli.access_token_resolver' => function ($connectionId = null) {
    if ($connectionId) {
        return MeliConnection::find($connectionId)?->access_token;
    }
    return auth()->user()?->meli_access_token;
}]);
```

## Products (Items)

```php
// Create
$product = Meli::products()->create([
    'title' => 'Product Name',
    'category_id' => 'MLB12345',
    'price' => 99.90,
    'currency_id' => 'BRL',
    'available_quantity' => 10,
    'buying_mode' => 'buy_it_now',
    'condition' => 'new',
    'listing_type_id' => 'gold_special',
]);

// Read — returns Product DTO
$product = Meli::products()->get('MLB123456789');
$product->id;          // string
$product->title;       // string
$product->price;       // float
$product->status;      // string
$product->extra;       // array (unmapped fields)

// Update
$product = Meli::products()->update('MLB123456789', ['price' => 89.90]);

// Change status
$product = Meli::products()->changeStatus('MLB123456789', 'paused');
```

## Orders

```php
// Single order
$order = Meli::orders()->get($orderId);
$order->status;        // string
$order->totalAmount;   // float
$order->orderItems;    // array<OrderItem>

// Search — returns SearchResult with Order DTOs
$result = Meli::orders()->search(['seller' => $sellerId]);
$result->results;  // array<Order>
$result->total;    // int
$result->offset;   // int
$result->limit;    // int

// Convenience methods
$paid = Meli::orders()->getPaidOrders();
$cancelled = Meli::orders()->getCancelledOrders();
$delivered = Meli::orders()->getDeliveredOrders();
$byDate = Meli::orders()->getByDateRange('2024-01-01T00:00:00', '2024-12-31T23:59:59');

// Lazy pagination — fetches pages on demand
Meli::orders()->searchAll(['seller' => $sellerId])->cursor()->each(fn (Order $o) => ...);
$allOrders = Meli::orders()->searchAll(['seller' => $sellerId])->all(); // Collection
```

## Payments

```php
$payment = Meli::payments()->get($paymentId);
$payment->status;              // string
$payment->transactionAmount;   // float
$payment->paymentMethodId;     // string

$approved = Meli::payments()->getApprovedPayments();
$byOrder = Meli::payments()->getByOrder($orderId);
$byDate = Meli::payments()->getByDateRange('2024-01-01', '2024-12-31');

// Lazy pagination
Meli::payments()->searchAll(['collector_id' => $sellerId])->cursor()->each(fn (Payment $p) => ...);
```

## Questions

```php
$question = Meli::questions()->get($questionId);
$question->text;      // string
$question->status;    // string (ANSWERED, UNANSWERED, etc.)
$question->answer;    // ?array

// Create question
$question = Meli::questions()->create($itemId, 'Is this available?');

// Answer question
Meli::questions()->answer($questionId, 'Yes, it is available.');

// Search
$unanswered = Meli::questions()->getUnansweredBySeller($sellerId);

// Lazy pagination
Meli::questions()->searchAll(['seller_id' => $sellerId])->cursor()->each(fn (Question $q) => ...);
```

## Users

```php
$me = Meli::users()->me();
$me->nickname;    // string
$me->email;       // string

$user = Meli::users()->get($userId);
$updated = Meli::users()->update($userId, ['nickname' => 'NewName']);
```

## Categories

```php
$category = Meli::categories()->get('MLB1234');
$category->name;            // string
$category->pathFromRoot;    // array

// These return raw Response (varied structures)
$sites = Meli::categories()->getSites();
$attrs = Meli::categories()->getAttributes('MLB1234');
$predicted = Meli::categories()->predictCategory('MLB', 'iPhone 15');
```

## Search Items

```php
$result = Meli::searchItem()->byQuery('iPhone');
$result->results;  // array (raw search result items)
$result->total;    // int

$bySeller = Meli::searchItem()->bySeller($sellerId);
$byCategory = Meli::searchItem()->byCategory('MLB1234');

// Multi-get — returns Product DTOs
$products = Meli::searchItem()->multiGetItems(['MLB123', 'MLB456']);

// Multi-get users — returns User DTOs
$users = Meli::searchItem()->multiGetUsers([123456, 789012]);

// Lazy pagination
Meli::searchItem()->byQueryAll('iPhone')->cursor()->each(fn ($item) => ...);
```

## Notifications (Webhooks)

```php
// In your webhook controller
public function handle(Request $request)
{
    $notification = $request->all();

    if (!Meli::notifications()->validateNotification($notification)) {
        abort(400);
    }

    $resource = Meli::notifications()->getResourceFromNotification($notification);

    // Or check topic
    if (Meli::notifications()->isTopicNotification($notification, 'orders_v2')) {
        // Handle order notification
    }
}

// Get missed notifications
$missed = Meli::notifications()->getMissedFeeds($appId);
$orderNotifs = Meli::notifications()->getOrdersNotifications($appId);
```

## Exception Handling

All API errors throw typed exceptions extending `MeliException`:

| Exception | HTTP Status | Use Case |
|---|---|---|
| `MeliAuthException` | 401 | Invalid/expired token |
| `MeliForbiddenException` | 403 | Insufficient permissions |
| `MeliNotFoundException` | 404 | Resource not found |
| `MeliValidationException` | 400 | Invalid request data (has `->causes` array) |
| `MeliRateLimitException` | 429 | Too many requests |
| `MeliServerException` | 500+ | ML server error |

```php
use Zdearo\Meli\Exceptions\MeliNotFoundException;
use Zdearo\Meli\Exceptions\MeliValidationException;
use Zdearo\Meli\Exceptions\MeliRateLimitException;
use Zdearo\Meli\Exceptions\MeliException;

try {
    $product = Meli::products()->create($data);
} catch (MeliValidationException $e) {
    Log::warning('Validation failed', [
        'causes' => $e->causes,
        'error' => $e->errorCode,
    ]);
} catch (MeliRateLimitException $e) {
    // Consider enabling retry: MELI_RETRY_TIMES=3
    dispatch(new RetryCreateProduct($data))->delay(now()->addMinutes(1));
} catch (MeliException $e) {
    Log::error("Meli API error [{$e->statusCode}]: {$e->getMessage()}", $e->context);
}
```

## DTO Structure

All DTOs are `readonly` classes with:
- Named properties for common fields (IDE autocomplete)
- `array $extra` for unmapped API fields
- Static `fromArray(array $data): static` factory

```php
// Accessing mapped fields
$order->totalAmount;  // float — typed, autocomplete-friendly

// Accessing unmapped fields
$order->extra['pack_id'];  // mixed — for fields not in the DTO
```

## Retry Configuration

Retry is **disabled by default** (`MELI_RETRY_TIMES=0`). To enable:

```env
MELI_RETRY_TIMES=3
MELI_RETRY_SLEEP_MS=500
```

Only retries on 429 (rate limit) and 5xx (server errors). Uses Laravel HTTP client's built-in retry with the configured sleep between attempts.

## Documentation Service (MCP)

```php
// Search ML technical documentation
$results = Meli::documentation()->search('items', 'pt_BR');

// Read a specific documentation page
$page = Meli::documentation()->getPage('/pt_br/product-search', 'pt_BR');

// List available MCP tools
$tools = Meli::documentation()->availableTools();
```

## MarketplaceEnum (19 Regions)

```php
use Zdearo\Meli\Enums\MarketplaceEnum;

$region = MarketplaceEnum::BRASIL;
$region->value;    // 'MLB'
$region->domain(); // 'mercadolivre.com.br'
```

Available: ARGENTINA (MLA), BRASIL (MLB), MEXICO (MLM), COLOMBIA (MCO), CHILE (MLC), PERU (MPE), URUGUAY (MLU), VENEZUELA (MLV), ECUADOR (MEC), BOLIVIA (MBO), COSTA_RICA (MCR), DOMINICANA (MRD), EL_SALVADOR (MSV), GUATEMALA (MGT), HONDURAS (MHN), NICARAGUA (MNI), PANAMA (MPA), PARAGUAY (MPY), CUBA (MCU).

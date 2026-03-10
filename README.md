# Mercado Libre PHP SDK

A modern Laravel package for integrating with the Mercado Libre API. Provides typed DTOs, custom exceptions, automatic pagination, optional retry, and a clean Facade interface.

## Features

- **Complete API Coverage**: 11 services covering all major Mercado Libre API endpoints
- **Typed DTOs**: Readonly data objects with IDE autocomplete (`Product`, `Order`, `User`, `Payment`, `Question`, `Category`, `AuthToken`)
- **Custom Exceptions**: Typed exceptions for every API error (`MeliNotFoundException`, `MeliValidationException`, `MeliAuthException`, etc.)
- **Lazy Pagination**: Automatic page-by-page iteration via `LazyCollection`
- **Optional Retry**: Configurable retry with backoff for rate limits (429) and server errors (5xx)
- **Multi-Tenancy**: Per-request token resolution with `withToken()` and `forConnection()`
- **Multi-Region**: Support for all 19 Latin American marketplaces
- **Laravel Boost**: Includes AI guidelines and skills for AI-assisted development
- **Modern PHP**: PHP 8.3+ with readonly classes, enums, and named arguments

## Requirements

- PHP 8.3+
- Laravel 9.0+

## Installation

```bash
composer require zdearo/meli-php
```

The package will be auto-discovered by Laravel.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=meli-config
```

Configure your environment variables in `.env`:

```env
MELI_REGION=BRASIL
MELI_CLIENT_ID=your-client-id
MELI_CLIENT_SECRET=your-client-secret
MELI_REDIRECT_URI=https://your-app.com/callback
MELI_AUTH_DOMAIN=mercadolibre.com.br
MELI_API_TOKEN=your-api-token

# Optional: retry on rate limit / server errors (disabled by default)
MELI_RETRY_TIMES=0
MELI_RETRY_SLEEP_MS=500
```

## Quick Start

### Basic Usage

```php
use Zdearo\Meli\Facades\Meli;

// Get a product — returns Product DTO
$product = Meli::products()->get('MLB123456789');
$product->title;       // string
$product->price;       // float
$product->status;      // string

// Search products — returns SearchResult DTO
$results = Meli::searchItem()->byQuery('smartphone samsung');
$results->results;     // array
$results->total;       // int

// Get user — returns User DTO
$user = Meli::users()->me();
$user->nickname;       // string

// Get orders — returns SearchResult with Order DTOs
$orders = Meli::orders()->getBySeller(123456789);
$orders->results;      // array<Order>
```

### Authentication (OAuth 2.0)

```php
use Zdearo\Meli\Facades\Meli;

// Generate authorization URL
$authUrl = Meli::getAuthUrl('your-state-parameter');

// Exchange code for token — returns AuthToken DTO
$token = Meli::auth()->getToken($code);
$token->accessToken;    // string
$token->refreshToken;   // string
$token->expiresIn;      // int

// Refresh token
$newToken = Meli::auth()->refreshToken($token->refreshToken);
```

### Multi-Tenancy

```php
// Per-request token
$user = Meli::withToken($accessToken)->users()->me();

// Via connection object
$orders = Meli::forConnection($meliConnection)->orders()->getBySeller($sellerId);

// Via resolver in config
config(['meli.access_token_resolver' => function ($connectionId = null) {
    if ($connectionId) {
        return MeliConnection::find($connectionId)?->access_token;
    }
    return auth()->user()?->meli_access_token;
}]);
```

### Working with Products

```php
// Create
$product = Meli::products()->create([
    'title' => 'Amazing Product',
    'category_id' => 'MLB1055',
    'price' => 99.99,
    'currency_id' => 'BRL',
    'available_quantity' => 10,
    'condition' => 'new',
]);

// Update
$product = Meli::products()->update('MLB123456789', [
    'price' => 89.99,
    'available_quantity' => 5,
]);

// Change status
$product = Meli::products()->changeStatus('MLB123456789', 'paused');
```

## Available Services

| Accessor | Service | Returns |
|---|---|---|
| `Meli::auth()` | AuthService | `AuthToken` |
| `Meli::products()` | ProductService | `Product` |
| `Meli::orders()` | OrderService | `Order`, `SearchResult` |
| `Meli::users()` | UserService | `User` |
| `Meli::payments()` | PaymentService | `Payment`, `SearchResult` |
| `Meli::questions()` | QuestionService | `Question`, `SearchResult` |
| `Meli::categories()` | CategoryService | `Category` |
| `Meli::searchItem()` | SearchItemService | `SearchResult` |
| `Meli::visits()` | VisitsService | `Response` |
| `Meli::notifications()` | NotificationService | `SearchResult` |
| `Meli::documentation()` | DocumentationService | `array` |

For detailed documentation of all services and methods, see [SERVICES.md](SERVICES.md).

## Error Handling

The package throws typed exceptions for all API errors:

```php
use Zdearo\Meli\Exceptions\MeliNotFoundException;
use Zdearo\Meli\Exceptions\MeliValidationException;
use Zdearo\Meli\Exceptions\MeliRateLimitException;
use Zdearo\Meli\Exceptions\MeliException;

try {
    $product = Meli::products()->get('MLB123456789');
} catch (MeliNotFoundException $e) {
    // 404 — resource not found
    $e->statusCode;  // 404
    $e->errorCode;   // "not_found"
    $e->context;     // full response body as array
} catch (MeliValidationException $e) {
    // 400 — validation errors
    $e->causes;      // array of validation errors from ML
} catch (MeliRateLimitException $e) {
    // 429 — too many requests
} catch (MeliException $e) {
    // Catch-all for any API error
    Log::error("Meli [{$e->statusCode}]: {$e->getMessage()}", $e->context);
}
```

| Exception | HTTP Status |
|---|---|
| `MeliAuthException` | 401 |
| `MeliForbiddenException` | 403 |
| `MeliNotFoundException` | 404 |
| `MeliValidationException` | 400 |
| `MeliRateLimitException` | 429 |
| `MeliServerException` | 500+ |
| `MeliException` | Any other |

## Lazy Pagination

Search methods return `SearchResult` DTOs with pagination metadata. Use `searchAll()` to iterate lazily:

```php
// Memory-efficient — fetches pages on demand
Meli::orders()
    ->searchAll(['seller' => $sellerId])
    ->cursor()
    ->each(function (\Zdearo\Meli\DTO\Order $order) {
        // Process each order
    });

// Collect all into a Collection
$allOrders = Meli::orders()->searchAll(['seller' => $sellerId])->all();
```

Available on: `OrderService`, `QuestionService`, `PaymentService`, `NotificationService`, `SearchItemService`.

## Retry Configuration

Retry is **disabled by default**. To enable automatic retry on rate limit (429) and server errors (5xx):

```env
MELI_RETRY_TIMES=3
MELI_RETRY_SLEEP_MS=500
```

## Example Controller

```php
use Zdearo\Meli\Facades\Meli;
use Zdearo\Meli\Exceptions\MeliNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $itemId)
    {
        try {
            $product = Meli::products()->get($itemId);
            return view('products.show', compact('product'));
        } catch (MeliNotFoundException) {
            abort(404);
        }
    }

    public function search(Request $request)
    {
        $results = Meli::searchItem()->byQuery($request->input('q'));
        return view('products.search', ['results' => $results->results, 'total' => $results->total]);
    }

    public function orders()
    {
        $orders = Meli::orders()->getBySeller(auth()->user()->meli_user_id);
        return view('orders.index', ['orders' => $orders->results]);
    }
}
```

## Documentation Service (MCP)

Access Mercado Libre's technical documentation programmatically via the MCP server:

```php
$results = Meli::documentation()->search('items', 'pt_BR');
$page = Meli::documentation()->getPage('/pt_br/product-search', 'pt_BR');
```

## Laravel Boost Support

This package includes AI guidelines and skills for [Laravel Boost](https://github.com/laravel/boost). When installed alongside Boost, running `php artisan boost:install` will automatically include Meli SDK guidelines and the `meli-development` skill.

## Testing

```bash
./vendor/bin/pest
```

## Code Style

```bash
./vendor/bin/pint
```

## License

MIT License. See [LICENSE](LICENSE) for more details.

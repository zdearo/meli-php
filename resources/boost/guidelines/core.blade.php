## Mercado Libre PHP SDK (zdearo/meli-php)

Laravel SDK for integrating with the Mercado Libre API. Provides typed services, DTOs, custom exceptions, automatic pagination, and optional retry with backoff.

### Key Concepts

- **Facade access**: Use `Meli::` to access all services (e.g., `Meli::products()`, `Meli::orders()`).
- **DTOs**: Services return typed readonly DTOs (`Product`, `Order`, `User`, `Payment`, `Question`, `Category`, `AuthToken`) instead of raw responses. Each DTO has an `$extra` array for unmapped fields.
- **Custom exceptions**: API errors throw typed exceptions (`MeliAuthException`, `MeliNotFoundException`, `MeliValidationException`, `MeliForbiddenException`, `MeliRateLimitException`, `MeliServerException`) that extend `MeliException`. Use specific catch blocks for granular error handling.
- **Pagination**: Search methods return `SearchResult` DTOs. Use `searchAll()` for lazy iteration via `LazyPaginator`.
- **Multi-tenancy**: Use `Meli::withToken($token)` or `Meli::forConnection($connection)` for per-request token resolution.

### Available Services

| Accessor | Service | Returns |
|---|---|---|
| `Meli::auth()` | `AuthService` | `AuthToken` |
| `Meli::products()` | `ProductService` | `Product` |
| `Meli::orders()` | `OrderService` | `Order`, `SearchResult` |
| `Meli::users()` | `UserService` | `User` |
| `Meli::payments()` | `PaymentService` | `Payment`, `SearchResult` |
| `Meli::questions()` | `QuestionService` | `Question`, `SearchResult` |
| `Meli::categories()` | `CategoryService` | `Category` |
| `Meli::searchItem()` | `SearchItemService` | `SearchResult` |
| `Meli::visits()` | `VisitsService` | `Response` |
| `Meli::notifications()` | `NotificationService` | `SearchResult` |
| `Meli::documentation()` | `DocumentationService` | `array` |

### Configuration

Publish with `php artisan vendor:publish --tag=meli-config`. Key env vars:

- `MELI_REGION` — Marketplace region enum (e.g., `BRASIL`, `ARGENTINA`)
- `MELI_CLIENT_ID`, `MELI_CLIENT_SECRET`, `MELI_REDIRECT_URI` — OAuth credentials
- `MELI_API_TOKEN` — Static fallback token
- `MELI_RETRY_TIMES` — Set to `0` (default, disabled) or `3` to enable automatic retry on 429/5xx
- `MELI_RETRY_SLEEP_MS` — Milliseconds between retries (default `500`)

### Exception Handling Pattern

@verbatim
<code-snippet name="Catching Meli exceptions" lang="php">
use Zdearo\Meli\Exceptions\MeliNotFoundException;
use Zdearo\Meli\Exceptions\MeliValidationException;
use Zdearo\Meli\Exceptions\MeliException;

try {
    $product = Meli::products()->get($itemId);
} catch (MeliNotFoundException $e) {
    // $e->statusCode, $e->errorCode, $e->context
} catch (MeliValidationException $e) {
    // $e->causes — array of validation errors
} catch (MeliException $e) {
    // Catch-all for any API error
}
</code-snippet>
@endverbatim

### Lazy Pagination Pattern

@verbatim
<code-snippet name="Iterate all orders lazily" lang="php">
Meli::orders()
    ->searchAll(['seller' => $sellerId])
    ->cursor()
    ->each(function (\Zdearo\Meli\DTO\Order $order) {
        // Process each order — pages fetched on demand
    });
</code-snippet>
@endverbatim

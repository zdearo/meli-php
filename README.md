# Mercado Libre PHP SDK

A simple and efficient PHP SDK for integrating with the Mercado Libre API, providing methods for authentication, product listing, orders, and other essential operations.

## Requirements

- PHP 8.0 or higher
- GuzzleHTTP 7.9
- ext-json

## Installation

You can install the package via composer:

```bash
composer require zdearo/meli-php
```

## Usage

### Basic Usage

```php
use Zdearo\Meli\Meli;

// Initialize the SDK with your region and API token
$meli = new Meli('BRASIL', 'your-api-token');

// Search for items
$results = $meli->searchItems()->byQuery('smartphone');

// Get a specific item
$item = $meli->products()->get('MLB123456789');

// Create a new item
$newItem = $meli->products()->create([
    'title' => 'Item title',
    'category_id' => 'MLB123',
    'price' => 100,
    'currency_id' => 'BRL',
    'available_quantity' => 10,
    'buying_mode' => 'buy_it_now',
    'listing_type_id' => 'gold_special',
    'condition' => 'new',
    'description' => 'Item description',
    'video_id' => 'youtube_video_id',
    'warranty' => '12 months',
    'pictures' => [
        ['source' => 'http://example.com/image.jpg'],
    ],
]);
```

### Authentication

```php
use Zdearo\Meli\Meli;

$meli = new Meli('BRASIL');

// Get the authorization URL
$authUrl = $meli->auth()->getAuthUrl(
    'https://your-app.com/callback',
    'your-client-id',
    'your-state'
);

// Redirect the user to the authorization URL
header('Location: ' . $authUrl);
exit;

// In your callback route, get the token
$token = $meli->auth()->getToken(
    'your-client-id',
    'your-client-secret',
    $_GET['code'],
    'https://your-app.com/callback'
);

// Store the token for future use
$accessToken = $token['access_token'];
$refreshToken = $token['refresh_token'];

// When the token expires, refresh it
$newToken = $meli->auth()->refreshToken(
    'your-client-id',
    'your-client-secret',
    $refreshToken
);
```

### Laravel Integration

This package includes Laravel integration. After installing the package, Laravel will automatically discover and register the service provider.

#### Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=meli-config
```

This will create a `config/meli.php` file where you can configure the SDK:

```php
// config/meli.php
return [
    'region' => env('MELI_REGION', 'BRASIL'),
    'api_token' => env('MELI_API_TOKEN', ''),
    'client_id' => env('MELI_CLIENT_ID', ''),
    'client_secret' => env('MELI_CLIENT_SECRET', ''),
    'redirect_uri' => env('MELI_REDIRECT_URI', ''),
    'timeout' => env('MELI_TIMEOUT', 10.0),
];
```

Add these variables to your `.env` file:

```
MELI_REGION=BRASIL
MELI_API_TOKEN=your-api-token
MELI_CLIENT_ID=your-client-id
MELI_CLIENT_SECRET=your-client-secret
MELI_REDIRECT_URI=https://your-app.com/callback
MELI_TIMEOUT=10.0
```

#### Usage in Laravel

The SDK is automatically registered in the service container, so you can inject it into your controllers or other classes:

```php
use Zdearo\Meli\Meli;

class ProductController extends Controller
{
    protected $meli;

    public function __construct(Meli $meli)
    {
        $this->meli = $meli;
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $results = $this->meli->searchItems()->byQuery($query);

        return view('products.search', ['results' => $results]);
    }
}
```

## Available Services

### Auth Service

- `getAuthUrl(string $redirectUri, string $clientId, string $state): string`
- `getToken(string $clientId, string $clientSecret, string $code, string $redirectUri): array`
- `refreshToken(string $clientId, string $clientSecret, string $refreshToken): array`

### Search Item Service

- `byQuery(string $value): array`
- `byCategory(string $categoryId): array`
- `byNickname(string $nickname): array`
- `bySeller(int $sellerId, ?string $categoryId = null): array`
- `byUserItems(int $userId, bool $scan = false): array`
- `multiGetItems(array $itemIds, array $attributes = []): array`
- `multiGetUsers(array $userIds): array`

### Product Service

- `create(array $productData): array`
- `get(string $itemId): array`
- `update(string $itemId, array $updateData): array`
- `changeStatus(string $itemId, string $status): array`

### Visits Service

- `totalByUser(int $userId, string $dateFrom, string $dateTo): array`
- `totalByItem(string $itemId): array`
- `totalByItemsDateRange(array $itemIds, string $dateFrom, string $dateTo): array`
- `visitsByUserTimeWindow(int $userId, int $last, string $unit, ?string $ending = null): array`
- `visitsByItemTimeWindow(string $itemId, int $last, string $unit, ?string $ending = null): array`

## Error Handling

The SDK throws `ApiException` when an API request fails. You can catch this exception to handle errors:

```php
use Zdearo\Meli\Exceptions\ApiException;

try {
    $item = $meli->products()->get('MLB123456789');
} catch (ApiException $e) {
    $statusCode = $e->getStatusCode();
    $message = $e->getMessage();
    
    // Handle the error
}
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
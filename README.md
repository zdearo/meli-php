# Mercado Libre PHP SDK

A modern Laravel package for integrating with the Mercado Libre API. Provides a clean interface using Laravel's native features with comprehensive marketplace functionality.

## Features

- 🚀 **Complete API Coverage**: Full support for all major Mercado Libre API endpoints
- 🎯 **Laravel Integration**: Built specifically for Laravel with Facades and Service Providers
- 🔧 **Modern PHP**: PHP 8.1+ with type hints and modern syntax
- 📦 **Service-Based Architecture**: Organized services for different API domains
- 🛡️ **Type Safety**: Full PHPDoc coverage and type declarations
- 🔄 **HTTP Client**: Built on Laravel's HTTP client with automatic error handling
- 🌍 **Multi-Region**: Support for all Latin American marketplaces

## Requirements

- PHP 8.1+
- Laravel 9.0+

## Installation

Install the package via Composer:

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
MELI_BASE_URL="https://api.mercadolibre.com/"
MELI_REGION=BRASIL
MELI_API_TOKEN=your-api-token
MELI_CLIENT_ID=your-client-id
MELI_CLIENT_SECRET=your-client-secret
MELI_REDIRECT_URI=https://your-app.com/callback
MELI_AUTH_DOMAIN=mercadolibre.com.br
```

## Quick Start

### Basic Usage with Facade

```php
use Zdearo\Meli\Facades\Meli;

// Get product information
$product = Meli::products()->get('MLB123456789');

// Search products
$results = Meli::searchItem()->byQuery('smartphone samsung');

// Get user information
$user = Meli::users()->get(123456789);

// Get orders
$orders = Meli::orders()->getBySeller(123456789);
```

### Authentication Flow

```php
use Zdearo\Meli\Facades\Meli;

// Generate authorization URL
$authUrl = Meli::getAuthUrl('your-state-parameter');

// Exchange code for access token
$token = Meli::auth()->getToken($authorizationCode);

// Refresh token
$newToken = Meli::auth()->refreshToken($refreshToken);
```

### Working with Products

```php
// Create a new product
$product = Meli::products()->create([
    'title' => 'Amazing Product',
    'category_id' => 'MLB1055',
    'price' => 99.99,
    'currency_id' => 'BRL',
    'available_quantity' => 10,
    'condition' => 'new'
]);

// Update product
Meli::products()->update('MLB123456789', [
    'price' => 89.99,
    'available_quantity' => 5
]);

// Change product status
Meli::products()->changeStatus('MLB123456789', 'paused');
```

## Available Services

This package provides comprehensive services for all major Mercado Libre API endpoints:

- **AuthService**: OAuth authentication and token management
- **ProductService**: Product CRUD operations and status management
- **SearchItemService**: Product search and discovery
- **UserService**: User information and account management
- **CategoryService**: Categories, attributes, and marketplace structure
- **OrderService**: Order management and sales tracking
- **QuestionService**: Q&A system for products
- **PaymentService**: Payment information and transaction details
- **NotificationService**: Webhook notifications and missed feeds
- **VisitsService**: Analytics and visit tracking

For detailed documentation of all services and methods, see [SERVICES.md](SERVICES.md).

## Example Controller

```php
use Zdearo\Meli\Facades\Meli;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(string $itemId)
    {
        try {
            $product = Meli::products()->get($itemId);
            return view('products.show', compact('product'));
        } catch (Exception $e) {
            return response()->json(['error' => 'Product not found'], 404);
        }
    }

    public function search(Request $request)
    {
        $results = Meli::searchItem()->byQuery($request->input('q'));
        return view('products.search', compact('results'));
    }

    public function orders()
    {
        $orders = Meli::orders()->getBySeller(auth()->user()->meli_user_id);
        return view('orders.index', compact('orders'));
    }
}
```

## Error Handling

The package uses Laravel's HTTP client, which throws `Illuminate\Http\Client\RequestException` for API errors:

```php
use Illuminate\Http\Client\RequestException;
use Zdearo\Meli\Facades\Meli;

try {
    $product = Meli::products()->get('MLB123456789');
} catch (RequestException $e) {
    Log::error('Mercado Libre API Error: ' . $e->getMessage());
    
    // Get response details
    $status = $e->response->status();
    $body = $e->response->body();
}
```

## Testing

```bash
./vendor/bin/pest
```

## Code Style

```bash
./vendor/bin/pint
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on how to contribute.

## License

MIT License. See [LICENSE](LICENSE) for more details.

## Credits

- Built for the Mercado Libre API
- Developed with Laravel best practices
- Inspired by the Laravel ecosystem
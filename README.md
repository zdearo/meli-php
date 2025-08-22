# Mercado Libre Laravel SDK

SDK Laravel para integração com a API do Mercado Libre. Fornece uma interface limpa e moderna usando recursos nativos do Laravel.

## Requisitos

- PHP 8.1+
- Laravel 9.0+

## Instalação

```bash
composer require zdearo/meli-php
```

O pacote será descoberto automaticamente pelo Laravel.

## Configuração

Publique o arquivo de configuração:

```bash
php artisan vendor:publish --tag=meli-config
```

Configure as variáveis no seu `.env`:

```env
MELI_BASE_URL="https://api.mercadolibre.com/"
MELI_REGION=BRASIL
MELI_API_TOKEN=your-api-token
MELI_CLIENT_ID=your-client-id
MELI_CLIENT_SECRET=your-client-secret
MELI_REDIRECT_URI=https://your-app.com/callback
MELI_AUTH_DOMAIN=mercadolibre.com.br
```

## Uso

### AuthService

Gerencia autenticação OAuth:

```php
use Zdearo\Meli\Services\AuthService;

// Gerar URL de autorização
$authUrl = app(AuthService::class)->getAuthUrl();

// Trocar código por token
$token = app(AuthService::class)->getToken(
    config('meli.client_id'),
    config('meli.client_secret'), 
    $code,
    config('meli.redirect_uri')
);

// Renovar token
$newToken = app(AuthService::class)->refreshToken(
    config('meli.client_id'),
    config('meli.client_secret'),
    $refreshToken
);
```

### ProductService

Gerencia produtos:

```php
use Zdearo\Meli\Services\ProductService;

$service = app(ProductService::class);

// Buscar produto
$produto = $service->get('MLB123456789');

// Criar produto
$novoProduto = $service->create([
    'title' => 'Produto Teste',
    'category_id' => 'MLB1055',
    'price' => 99.99,
    'currency_id' => 'BRL',
    'available_quantity' => 10,
    'condition' => 'new'
]);

// Atualizar produto
$service->update('MLB123456789', [
    'price' => 89.99,
    'available_quantity' => 5
]);

// Alterar status
$service->changeStatus('MLB123456789', 'paused');
```

### SearchItemService

Busca itens e usuários:

```php
use Zdearo\Meli\Services\SearchItemService;

$service = app(SearchItemService::class);

// Buscar por query
$resultados = $service->byQuery('smartphone samsung');

// Buscar por categoria
$resultados = $service->byCategory('MLB1055');

// Buscar por vendedor
$resultados = $service->bySeller(123456789);

// Buscar itens do usuário
$itens = $service->byUserItems(123456789);

// Buscar múltiplos itens
$itens = $service->multiGetItems(['MLB123', 'MLB456']);
```

### VisitsService

Estatísticas de visitas:

```php
use Zdearo\Meli\Services\VisitsService;

$service = app(VisitsService::class);

// Visitas por usuário
$visitas = $service->totalByUser(123456789, '2024-01-01', '2024-12-31');

// Visitas por item
$visitas = $service->totalByItem('MLB123456789');

// Visitas em janela de tempo
$visitas = $service->visitsByUserTimeWindow(123456789, 30, 'day');
```

## Injeção de Dependência

Use injeção de dependência em seus controllers:

```php
class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private SearchItemService $searchService
    ) {}

    public function show(string $itemId)
    {
        $product = $this->productService->get($itemId);
        return view('products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $results = $this->searchService->byQuery($request->q);
        return view('products.search', compact('results'));
    }
}
```

## Tratamento de Erros

O SDK usa o HTTP Client do Laravel:

```php
use Illuminate\Http\Client\RequestException;

try {
    $product = $productService->get('MLB123456789');
} catch (RequestException $e) {
    Log::error('Erro API Meli: ' . $e->getMessage());
}
```

## Licença

MIT License. Veja [LICENSE](LICENSE) para mais detalhes.
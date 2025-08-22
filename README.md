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

### Usando a Facade (Recomendado)

A forma mais simples é usar a Facade `Meli`:

```php
use Zdearo\Meli\Facades\Meli;

// Autenticação
$authUrl = Meli::getAuthUrl();
$token = Meli::auth()->getToken($clientId, $clientSecret, $code, $redirectUri);
$newToken = Meli::auth()->refreshToken($clientId, $clientSecret, $refreshToken);

// Produtos
$produto = Meli::products()->get('MLB123456789');
$novoProduto = Meli::products()->create([
    'title' => 'Produto Teste',
    'category_id' => 'MLB1055',
    'price' => 99.99,
    'currency_id' => 'BRL',
    'available_quantity' => 10,
    'condition' => 'new'
]);
Meli::products()->update('MLB123456789', ['price' => 89.99]);
Meli::products()->changeStatus('MLB123456789', 'paused');

// Busca
$resultados = Meli::search()->byQuery('smartphone samsung');
$resultados = Meli::search()->byCategory('MLB1055');
$resultados = Meli::search()->bySeller(123456789);
$itens = Meli::search()->byUserItems(123456789);
$itens = Meli::search()->multiGetItems(['MLB123', 'MLB456']);

// Visitas
$visitas = Meli::visits()->totalByUser(123456789, '2024-01-01', '2024-12-31');
$visitas = Meli::visits()->totalByItem('MLB123456789');
$visitas = Meli::visits()->visitsByUserTimeWindow(123456789, 30, 'day');
```

### Usando Services Diretos

Você também pode usar os services diretamente:

```php
use Zdearo\Meli\Services\{AuthService, ProductService, SearchItemService, VisitsService};

// Com injeção de dependência
$produto = app(ProductService::class)->get('MLB123456789');
$resultados = app(SearchItemService::class)->byQuery('smartphone');
```

## Exemplo em Controllers

```php
use Zdearo\Meli\Facades\Meli;

class ProductController extends Controller
{
    public function show(string $itemId)
    {
        $product = Meli::products()->get($itemId);
        return view('products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $results = Meli::search()->byQuery($request->q);
        return view('products.search', compact('results'));
    }

    public function auth()
    {
        return redirect(Meli::getAuthUrl());
    }

    public function callback(Request $request)
    {
        $token = Meli::auth()->getToken(
            config('meli.client_id'),
            config('meli.client_secret'),
            $request->code,
            config('meli.redirect_uri')
        );
        
        session(['meli_token' => $token]);
        return redirect('/dashboard');
    }
}
```

## Tratamento de Erros

```php
use Illuminate\Http\Client\RequestException;
use Zdearo\Meli\Facades\Meli;

try {
    $product = Meli::products()->get('MLB123456789');
} catch (RequestException $e) {
    Log::error('Erro API Meli: ' . $e->getMessage());
}
```

## Licença

MIT License. Veja [LICENSE](LICENSE) para mais detalhes.
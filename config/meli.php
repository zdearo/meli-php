<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mercado Libre API Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration for the Mercado Libre API
    | integration. You can specify the region and API token here.
    |
    */

    'base_url' => env('MELI_BASE_URL', 'https://api.mercadolibre.com/'),

    /*
    |--------------------------------------------------------------------------
    | Region
    |--------------------------------------------------------------------------
    |
    | The region to use for the Mercado Libre API. Available options are:
    | ARGENTINA, BOLIVIA, BRASIL, CHILE, COLOMBIA, COSTA_RICA, CUBA,
    | DOMINICANA, ECUADOR, EL_SALVADOR, GUATEMALA, HONDURAS, MEXICO,
    | NICARAGUA, PANAMA, PARAGUAY, PERU, URUGUAY, VENEZUELA
    |
    */
    'region' => env('MELI_REGION', 'BRASIL'),

    /*
    |--------------------------------------------------------------------------
    | Client ID
    |--------------------------------------------------------------------------
    |
    | The client ID for your Mercado Libre application.
    |
    */
    'client_id' => env('MELI_CLIENT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Client Secret
    |--------------------------------------------------------------------------
    |
    | The client secret for your Mercado Libre application.
    |
    */
    'client_secret' => env('MELI_CLIENT_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Redirect URI
    |--------------------------------------------------------------------------
    |
    | The redirect URI for the OAuth flow.
    |
    */
    'redirect_uri' => env('MELI_REDIRECT_URI', ''),

    /*
    |--------------------------------------------------------------------------
    | Auth Domain
    |--------------------------------------------------------------------------
    |
    | The authentication domain for the OAuth flow. This varies by region:
    | - Brasil: mercadolibre.com.br
    | - Argentina: mercadolibre.com.ar
    | - Mexico: mercadolibre.com.mx
    | - Colombia: mercadolibre.com.co
    | - Chile: mercadolibre.cl
    | - Peru: mercadolibre.com.pe
    | - Uruguay: mercadolibre.com.uy
    | - Venezuela: mercadolibre.com.ve
    | - Ecuador: mercadolibre.com.ec
    | - Costa Rica: mercadolibre.co.cr
    | - Panama: mercadolibre.com.pa
    | - Bolivia: mercadolibre.com.bo
    | - Paraguay: mercadolibre.com.py
    | - Guatemala: mercadolibre.com.gt
    | - Nicaragua: mercadolibre.com.ni
    | - Honduras: mercadolibre.com.hn
    | - El Salvador: mercadolibre.com.sv
    | - Dominicana: mercadolibre.com.do
    | - Cuba: mercadolibre.com.cu
    |
    */
    'auth_domain' => env('MELI_AUTH_DOMAIN', 'mercadolibre.com.br'),

    /*
    |--------------------------------------------------------------------------
    | API Token (Static)
    |--------------------------------------------------------------------------
    |
    | A static API token for authentication. This is used as fallback when
    | no access_token_resolver is configured.
    |
    */
    'api_token' => env('MELI_API_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Access Token Resolver
    |--------------------------------------------------------------------------
    |
    | This configuration allows you to dynamically resolve access tokens
    | for API requests. You can configure this in several ways:
    |
    | 1. Closure/Callable: Return a closure that resolves the token
    | 2. Class name: Specify a class that implements a 'resolve()' method
    | 3. null: Use the static 'api_token' configuration above
    |
    | Examples:
    |
    | // Global resolver in your AppServiceProvider:
    | config(['meli.access_token_resolver' => function () {
    |     return auth()->user()?->meli_access_token;
    | }]);
    |
    | // Context-aware resolver for forConnection() method:
    | config(['meli.access_token_resolver' => function ($connectionId = null) {
    |     if ($connectionId) {
    |         return MeliConnection::find($connectionId)?->access_token;
    |     }
    |     return auth()->user()?->meli_access_token;
    | }]);
    |
    | // Using a custom resolver class:
    | config(['meli.access_token_resolver' => App\Services\MeliTokenResolver::class]);
    |
    */
    'access_token_resolver' => null,
];

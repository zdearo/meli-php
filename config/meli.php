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
    | API Token
    |--------------------------------------------------------------------------
    |
    | The API token to use for authentication with the Mercado Libre API.
    | This can be obtained through the OAuth flow.
    |
    */
    'api_token' => env('MELI_API_TOKEN', ''),

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
];

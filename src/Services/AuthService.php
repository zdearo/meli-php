<?php

namespace zdearo\Meli\Services;

use zdearo\Meli\Http\MeliClient;
use GuzzleHttp\Exception\RequestException;

class AuthService
{
    private MeliClient $client;
    private string $uri;

    public function __construct(MeliClient $client, string $uri)
    {
        $this->client = $client;
        $this->uri = $uri;
    }

    public function getAuthUrl(string $redirectUri, string $clientId): string
    {
        return "https://auth.{$this->uri}/authorization?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}";
    }

    public function getToken(string $clientId, string $clientSecret, string $code, string $redirectUri)
    {
        try {
            $response = $this->client->getClient()->post('http://api.mercadolibre.com/oauth/token', [
                'form_params' => [
                    'grant_type'    => 'authorization_code',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'code'          => $code,
                    'redirect_uri'  => $redirectUri,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->client->handleRequestException($e)['message'];
        }
    }

    public function refreshToken(string $clientId, string $clientSecret, string $refreshToken)
    {
        try {
            $response = $this->client->getClient()->post('http://api.mercadolibre.com/oauth/token', [
                'form_params' => [
                    'grant_type'    => 'refresh_token',
                    'client_id'     => $clientId,
                    'client_secret' => $clientSecret,
                    'refresh_token' => $refreshToken,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return $this->client->handleRequestException($e)['message'];
        }
    }
}


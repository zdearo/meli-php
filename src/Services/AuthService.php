<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use InvalidArgumentException;
use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for handling authentication with the Mercado Libre API.
 */
class AuthService
{
    /**
     * Get an access token using an authorization code.
     *
     * @param  string  $code  The authorization code
     * @return Response The response (use ->json() to get array data)
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     * @throws InvalidArgumentException If the required config is missing
     */
    public function getToken(string $code): Response
    {
        $this->validateConfig();

        return ApiRequest::post('oauth/token')
            ->withBody([
                'grant_type' => 'authorization_code',
                'client_id' => config('meli.client_id'),
                'client_secret' => config('meli.client_secret'),
                'code' => $code,
                'redirect_uri' => config('meli.redirect_uri'),
            ])
            ->send();
    }

    /**
     * Refresh an access token using a refresh token.
     *
     * @param  string  $refreshToken  The refresh token
     * @return Response The response (use ->json() to get array data)
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     * @throws InvalidArgumentException If the required config is missing
     */
    public function refreshToken(string $refreshToken): Response
    {
        $this->validateConfig();

        return ApiRequest::post('oauth/token')
            ->withBody([
                'grant_type' => 'refresh_token',
                'client_id' => config('meli.client_id'),
                'client_secret' => config('meli.client_secret'),
                'refresh_token' => $refreshToken,
            ])
            ->send();
    }

    /**
     * Validate required configuration.
     *
     * @throws InvalidArgumentException If the required config is missing
     */
    private function validateConfig(): void
    {
        $required = ['client_id', 'client_secret', 'redirect_uri'];

        foreach ($required as $key) {
            if (empty(config("meli.{$key}"))) {
                throw new InvalidArgumentException("Missing required config: meli.{$key}");
            }
        }
    }
}

<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Enums\MarketplaceEnum;
use Zdearo\Meli\Exceptions\ApiException;
use Zdearo\Meli\Http\MeliClient;

/**
 * Service for handling authentication with the Mercado Libre API.
 */
class AuthService extends BaseService
{
    /**
     * The marketplace domain.
     *
     * @var string
     */
    private string $uri;

    /**
     * Create a new auth service instance.
     *
     * @param MarketplaceEnum $region The marketplace region
     * @param MeliClient $client The HTTP client
     */
    public function __construct(MarketplaceEnum $region, MeliClient $client)
    {
        parent::__construct($client);
        $this->uri = $region->domain();
    }

    /**
     * Get the authorization URL for the OAuth flow.
     *
     * @param string $redirectUri The redirect URI after authorization
     * @param string $clientId The client ID
     * @param string $state A state parameter for security
     * @return string The authorization URL
     */
    public function getAuthUrl(string $redirectUri, string $clientId, string $state): string
    {
        return "https://auth.{$this->uri}/authorization?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}";
    }

    /**
     * Get an access token using an authorization code.
     *
     * @param string $clientId The client ID
     * @param string $clientSecret The client secret
     * @param string $code The authorization code
     * @param string $redirectUri The redirect URI
     * @return array<string, mixed> The token response
     * @throws ApiException If the request fails
     */
    public function getToken(string $clientId, string $clientSecret, string $code, string $redirectUri): array
    {
        return $this->request('POST', 'https://api.mercadolibre.com/oauth/token', [
            'grant_type'    => 'authorization_code',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'code'          => $code,
            'redirect_uri'  => $redirectUri,
        ]);
    }

    /**
     * Refresh an access token using a refresh token.
     *
     * @param string $clientId The client ID
     * @param string $clientSecret The client secret
     * @param string $refreshToken The refresh token
     * @return array<string, mixed> The token response
     * @throws ApiException If the request fails
     */
    public function refreshToken(string $clientId, string $clientSecret, string $refreshToken): array
    {
        return $this->request('POST', 'https://api.mercadolibre.com/oauth/token', [
            'grant_type'    => 'refresh_token',
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $refreshToken,
        ]);
    }
}

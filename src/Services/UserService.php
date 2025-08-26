<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for managing users in the Mercado Libre API.
 */
class UserService
{
    /**
     * Get user information by user ID.
     *
     * @param  int  $userId  The user ID
     * @return array<string, mixed> The user information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function get(int $userId): array
    {
        return ApiRequest::get("users/{$userId}")
            ->send()
            ->json();
    }

    /**
     * Get authenticated user information.
     *
     * @return array<string, mixed> The authenticated user information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function me(): array
    {
        return ApiRequest::get('users/me')
            ->send()
            ->json();
    }

    /**
     * Update user information.
     *
     * @param  int  $userId  The user ID
     * @param  array<string, mixed>  $userData  The user data to update
     * @return array<string, mixed> The updated user information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function update(int $userId, array $userData): array
    {
        return ApiRequest::put("users/{$userId}")
            ->withBody($userData)
            ->send()
            ->json();
    }

    /**
     * Get user addresses.
     *
     * @param  int  $userId  The user ID
     * @return array<string, mixed> The user addresses
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAddresses(int $userId): array
    {
        return ApiRequest::get("users/{$userId}/addresses")
            ->send()
            ->json();
    }

    /**
     * Get user's accepted payment methods.
     *
     * @param  int  $userId  The user ID
     * @return array<string, mixed> The accepted payment methods
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAcceptedPaymentMethods(int $userId): array
    {
        return ApiRequest::get("users/{$userId}/accepted_payment_methods")
            ->send()
            ->json();
    }

    /**
     * Get user's brands.
     *
     * @param  int  $userId  The user ID
     * @return array<string, mixed> The user's brands
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getBrands(int $userId): array
    {
        return ApiRequest::get("users/{$userId}/brands")
            ->send()
            ->json();
    }

    /**
     * Get user's available listing types.
     *
     * @param  int  $userId  The user ID
     * @param  string|null  $categoryId  Optional category ID
     * @return array<string, mixed> The available listing types
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAvailableListingTypes(int $userId, ?string $categoryId = null): array
    {
        $endpoint = "users/{$userId}/available_listing_types";
        $request = ApiRequest::get($endpoint);

        if ($categoryId) {
            $request->withQuery(['category_id' => $categoryId]);
        }

        return $request->send()->json();
    }

    /**
     * Get specific available listing type.
     *
     * @param  int  $userId  The user ID
     * @param  string  $listingTypeId  The listing type ID
     * @param  string|null  $categoryId  Optional category ID
     * @return array<string, mixed> The listing type availability
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAvailableListingType(int $userId, string $listingTypeId, ?string $categoryId = null): array
    {
        $endpoint = "users/{$userId}/available_listing_type/{$listingTypeId}";
        $request = ApiRequest::get($endpoint);

        if ($categoryId) {
            $request->withQuery(['category_id' => $categoryId]);
        }

        return $request->send()->json();
    }

    /**
     * Get user's classifieds promotion packs.
     *
     * @param  int  $userId  The user ID
     * @return array<string, mixed> The promotion packs
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getClassifiedsPromotionPacks(int $userId): array
    {
        return ApiRequest::get("users/{$userId}/classifieds_promotion_packs")
            ->send()
            ->json();
    }

    /**
     * Associate a new promotion pack for a user.
     *
     * @param  int  $userId  The user ID
     * @param  array<string, mixed>  $packData  The promotion pack data
     * @return array<string, mixed> The created promotion pack
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function createClassifiedsPromotionPack(int $userId, array $packData): array
    {
        return ApiRequest::post("users/{$userId}/classifieds_promotion_packs")
            ->withBody($packData)
            ->send()
            ->json();
    }

    /**
     * Check if user has available listings for a specific type and category.
     *
     * @param  int  $userId  The user ID
     * @param  string  $listingType  The listing type
     * @param  string  $categoryId  The category ID
     * @return array<string, mixed> The availability information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function checkListingAvailability(int $userId, string $listingType, string $categoryId): array
    {
        return ApiRequest::get("users/{$userId}/classifieds_promotion_packs/{$listingType}")
            ->withQuery(['categoryId' => $categoryId])
            ->send()
            ->json();
    }

    /**
     * Revoke application permission for a user.
     *
     * @param  int  $userId  The user ID
     * @param  int  $applicationId  The application ID
     * @return Response The response
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function revokeApplicationPermission(int $userId, int $applicationId): Response
    {
        return ApiRequest::delete("users/{$userId}/applications/{$applicationId}")
            ->send();
    }
}
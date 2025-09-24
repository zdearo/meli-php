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
     * @return Response The user information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function get(int $userId): Response
    {
        return ApiRequest::get("users/{$userId}")
            ->send();
    }

    /**
     * Get authenticated user information.
     *
     * @return Response The authenticated user information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function me(): Response
    {
        return ApiRequest::get('users/me')
            ->send();
    }

    /**
     * Update user information.
     *
     * @param  int  $userId  The user ID
     * @param  array<string, mixed>  $userData  The user data to update
     * @return Response The updated user information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function update(int $userId, array $userData): Response
    {
        return ApiRequest::put("users/{$userId}")
            ->withBody($userData)
            ->send();
    }

    /**
     * Get user addresses.
     *
     * @param  int  $userId  The user ID
     * @return Response The user addresses
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAddresses(int $userId): Response
    {
        return ApiRequest::get("users/{$userId}/addresses")
            ->send();
    }

    /**
     * Get user's accepted payment methods.
     *
     * @param  int  $userId  The user ID
     * @return Response The accepted payment methods
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAcceptedPaymentMethods(int $userId): Response
    {
        return ApiRequest::get("users/{$userId}/accepted_payment_methods")
            ->send();
    }

    /**
     * Get user's brands.
     *
     * @param  int  $userId  The user ID
     * @return Response The user's brands
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getBrands(int $userId): Response
    {
        return ApiRequest::get("users/{$userId}/brands")
            ->send();
    }

    /**
     * Get user's available listing types.
     *
     * @param  int  $userId  The user ID
     * @param  string|null  $categoryId  Optional category ID
     * @return Response The available listing types
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAvailableListingTypes(int $userId, ?string $categoryId = null): Response
    {
        $endpoint = "users/{$userId}/available_listing_types";
        $request = ApiRequest::get($endpoint);

        if ($categoryId) {
            $request->withQuery(['category_id' => $categoryId]);
        }

        return $request->send();
    }

    /**
     * Get specific available listing type.
     *
     * @param  int  $userId  The user ID
     * @param  string  $listingTypeId  The listing type ID
     * @param  string|null  $categoryId  Optional category ID
     * @return Response The listing type availability
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getAvailableListingType(int $userId, string $listingTypeId, ?string $categoryId = null): Response
    {
        $endpoint = "users/{$userId}/available_listing_type/{$listingTypeId}";
        $request = ApiRequest::get($endpoint);

        if ($categoryId) {
            $request->withQuery(['category_id' => $categoryId]);
        }

        return $request->send();
    }

    /**
     * Get user's classifieds promotion packs.
     *
     * @param  int  $userId  The user ID
     * @return Response The promotion packs
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function getClassifiedsPromotionPacks(int $userId): Response
    {
        return ApiRequest::get("users/{$userId}/classifieds_promotion_packs")
            ->send();
    }

    /**
     * Associate a new promotion pack for a user.
     *
     * @param  int  $userId  The user ID
     * @param  array<string, mixed>  $packData  The promotion pack data
     * @return Response The created promotion pack
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function createClassifiedsPromotionPack(int $userId, array $packData): Response
    {
        return ApiRequest::post("users/{$userId}/classifieds_promotion_packs")
            ->withBody($packData)
            ->send();
    }

    /**
     * Check if user has available listings for a specific type and category.
     *
     * @param  int  $userId  The user ID
     * @param  string  $listingType  The listing type
     * @param  string  $categoryId  The category ID
     * @return Response The availability information
     *
     * @throws IlluminateHttpClientRequestException If the request fails
     */
    public function checkListingAvailability(int $userId, string $listingType, string $categoryId): Response
    {
        return ApiRequest::get("users/{$userId}/classifieds_promotion_packs/{$listingType}")
            ->withQuery(['categoryId' => $categoryId])
            ->send();
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

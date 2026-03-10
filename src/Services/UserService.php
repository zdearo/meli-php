<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\DTO\User;
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
     * @return User The user information
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function get(int $userId): User
    {
        $response = ApiRequest::get("users/{$userId}")
            ->send();

        return User::fromArray($response->json());
    }

    /**
     * Get authenticated user information.
     *
     * @return User The authenticated user information
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function me(): User
    {
        $response = ApiRequest::get('users/me')
            ->send();

        return User::fromArray($response->json());
    }

    /**
     * Update user information.
     *
     * @param  int  $userId  The user ID
     * @param  array<string, mixed>  $userData  The user data to update
     * @return User The updated user information
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function update(int $userId, array $userData): User
    {
        $response = ApiRequest::put("users/{$userId}")
            ->withBody($userData)
            ->send();

        return User::fromArray($response->json());
    }

    /**
     * Get user addresses.
     *
     * @param  int  $userId  The user ID
     * @return Response The user addresses
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
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
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
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
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
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
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getAvailableListingTypes(int $userId, ?string $categoryId = null): Response
    {
        $request = ApiRequest::get("users/{$userId}/available_listing_types");

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
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getAvailableListingType(int $userId, string $listingTypeId, ?string $categoryId = null): Response
    {
        $request = ApiRequest::get("users/{$userId}/available_listing_type/{$listingTypeId}");

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
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
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
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
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
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
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
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function revokeApplicationPermission(int $userId, int $applicationId): Response
    {
        return ApiRequest::delete("users/{$userId}/applications/{$applicationId}")
            ->send();
    }
}

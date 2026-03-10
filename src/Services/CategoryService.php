<?php

namespace Zdearo\Meli\Services;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\DTO\Category;
use Zdearo\Meli\Support\ApiRequest;

/**
 * Service for managing categories in the Mercado Libre API.
 */
class CategoryService
{
    /**
     * Get all sites available in Mercado Libre.
     *
     * @return Response The available sites
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getSites(): Response
    {
        return ApiRequest::get('sites')
            ->send();
    }

    /**
     * Get all categories for a specific site.
     *
     * @param  string  $siteId  The site ID (e.g., MLB, MLA)
     * @return Response The categories
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getCategories(string $siteId): Response
    {
        return ApiRequest::get("sites/{$siteId}/categories")
            ->send();
    }

    /**
     * Get category details by ID.
     *
     * @param  string  $categoryId  The category ID
     * @return Category The category details
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function get(string $categoryId): Category
    {
        $data = ApiRequest::get("categories/{$categoryId}")
            ->send()
            ->json();

        return Category::fromArray($data);
    }

    /**
     * Get category attributes.
     *
     * @param  string  $categoryId  The category ID
     * @return Response The category attributes
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getAttributes(string $categoryId): Response
    {
        return ApiRequest::get("categories/{$categoryId}/attributes")
            ->send();
    }

    /**
     * Get listing types for a site.
     *
     * @param  string  $siteId  The site ID
     * @return Response The listing exposures
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getListingExposures(string $siteId): Response
    {
        return ApiRequest::get("sites/{$siteId}/listing_exposures")
            ->send();
    }

    /**
     * Get listing prices for a site.
     *
     * @param  string  $siteId  The site ID
     * @param  float  $price  The price to check
     * @return Response The listing prices
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getListingPrices(string $siteId, float $price): Response
    {
        return ApiRequest::get("sites/{$siteId}/listing_prices")
            ->withQuery(['price' => $price])
            ->send();
    }

    /**
     * Predict category based on title or attributes.
     *
     * @param  string  $siteId  The site ID
     * @param  string  $query  The search query (title, description, etc.)
     * @param  int|null  $limit  Optional limit of results
     * @return Response The predicted categories
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function predictCategory(string $siteId, string $query, ?int $limit = null): Response
    {
        $queryParams = ['q' => $query];

        if ($limit) {
            $queryParams['limit'] = $limit;
        }

        return ApiRequest::get("sites/{$siteId}/domain_discovery/search")
            ->withQuery($queryParams)
            ->send();
    }

    /**
     * Get classifieds promotion packs for a category.
     *
     * @param  string  $categoryId  The category ID
     * @return Response The promotion packs
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getClassifiedsPromotionPacks(string $categoryId): Response
    {
        return ApiRequest::get("categories/{$categoryId}/classifieds_promotion_packs")
            ->send();
    }

    /**
     * Get technical specs for a domain.
     *
     * @param  string  $domainId  The domain ID
     * @return Response The technical specifications
     *
     * @throws \Zdearo\Meli\Exceptions\MeliException If the request fails
     */
    public function getDomainTechnicalSpecs(string $domainId): Response
    {
        return ApiRequest::get("domains/{$domainId}/technical_specs")
            ->send();
    }
}

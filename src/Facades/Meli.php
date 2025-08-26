<?php

namespace Zdearo\Meli\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getAuthUrl(string $state = null)
 * @method static \Illuminate\Http\Client\Response send(\Zdearo\Meli\Support\ApiRequest $request)
 * @method static \Zdearo\Meli\Services\AuthService auth()
 * @method static \Zdearo\Meli\Services\ProductService products()
 * @method static \Zdearo\Meli\Services\SearchItemService searchItem()
 * @method static \Zdearo\Meli\Services\VisitsService visits()
 * @method static \Zdearo\Meli\Services\UserService users()
 * @method static \Zdearo\Meli\Services\CategoryService categories()
 * @method static \Zdearo\Meli\Services\OrderService orders()
 * @method static \Zdearo\Meli\Services\QuestionService questions()
 * @method static \Zdearo\Meli\Services\NotificationService notifications()
 * @method static \Zdearo\Meli\Services\PaymentService payments()
 *
 * @see \Zdearo\Meli\Support\MeliApiClient
 */
class Meli extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'meli';
    }
}

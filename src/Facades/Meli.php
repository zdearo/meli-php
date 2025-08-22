<?php

namespace Zdearo\Meli\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string getAuthUrl(string $state = null)
 * @method static string generateState()
 * @method static \Illuminate\Http\Client\Response send(\Zdearo\Meli\Support\ApiRequest $request)
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

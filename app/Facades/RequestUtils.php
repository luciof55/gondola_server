<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static boolean isCurrentMenu(string $menuUrl, string $menuKey)
 *
 */
class RequestUtils extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'requtils';
    }
}

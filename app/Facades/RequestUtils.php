<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static boolean isCurrentMenu(string $menuUrl, string $menuKey)
 * @method static boolean routeController(string $entity, string $controller, string $model, array $middlewares)
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

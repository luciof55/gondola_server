<?php

namespace App\Http\Helper;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class RequestHelper
{
    /**
     * Resolve is the current menu item based on menu name and url path.
     * @param "menu": menu name
     * @return boolean
     */
    public function isCurrentMenu(string $menuKey) {
      Log::info('menuKey: '.$menuKey);
      $routeCollection = Route::getRoutes();
      foreach ($routeCollection as $value) {
        Log::info('POS: '.strpos($value->getName(), $menuKey));
        if ($value->getName() != '' && (strpos($value->getName(), $menuKey) !== false) && $value->matches(URL::getRequest())) {
            Log::info('Found: '.$value->uri().' - '.$value->getName());
            return true;
        }
      }
      return false;
    }
}

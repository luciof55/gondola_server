<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;

class RouteGondolaServiceProvider extends RouteServiceProvider
{
    /**
     * Extends the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        parent::mapWebRoutes();
		Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web_gondola.php'));
    }
}

<?php

namespace App\Providers;

use App\Providers\AppServiceProvider;
use App\Http\Helper\RequestHelper;

class RequestUtilsServiceProvider extends AppServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {	
		$this->app->alias('requtils', RequestHelper::class);
		$this->app->singleton('requtils', '\App\Http\Helper\RequestHelper');
    }
}

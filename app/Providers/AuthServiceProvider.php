<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\SecurityPolicy', 
		'App\User' => 'App\Policies\SecurityPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
		
		Gate::define('enable', 'App\Policies\SecurityPolicy@enable');
		Gate::define('remove', 'App\Policies\SecurityPolicy@remove');
		Gate::define('create', 'App\Policies\SecurityPolicy@create');
		Gate::define('store', 'App\Policies\SecurityPolicy@store');
		Gate::define('edit', 'App\Policies\SecurityPolicy@edit');
		Gate::define('update', 'App\Policies\SecurityPolicy@update');
		Gate::define('view', 'App\Policies\SecurityPolicy@view');
		Gate::define('show', 'App\Policies\SecurityPolicy@show');
		Gate::define('module', 'App\Policies\SecurityPolicy@module');

        //
    }
}

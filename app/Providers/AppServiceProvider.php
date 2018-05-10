<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		Validator::extend('MultipleUniqueRule', 'MultipleUniqueRule@passes');
		Validator::extend('ForeignKeyRule', 'ForeignKeyRule@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
		if ($this->app->environment() == 'local') {
			$this->app->register('Kurt\Repoist\RepoistServiceProvider');
		}
	
		$this->app->bind(
			'App\Repositories\Contracts\ProfileRepository', 'App\Repositories\Eloquent\EloquentProfileRepository'
		);
		
		$this->app->bind(
			'App\Repositories\Contracts\RoleRepository', 'App\Repositories\Eloquent\EloquentRoleRepository'
		);
		
		$this->app->bind(
			'App\Repositories\Contracts\ProfileRoleRepository', 'App\Repositories\Eloquent\EloquentProfileRoleRepository'
		);
		
		$this->app->bind(
			'App\Repositories\Contracts\ResourceRepository', 'App\Repositories\Eloquent\EloquentResourceRepository'
		);
		
		$this->app->bind(
			'App\Repositories\Contracts\PrivilegeRepository', 'App\Repositories\Eloquent\EloquentPrivilegeRepository'
		);
		
		$this->app->bind(
			'App\Repositories\Contracts\UserRepository', 'App\Repositories\Eloquent\EloquentUserRepository'
		);
		
		$this->app->bind(
			'App\Repositories\Contracts\MenuItemRepository', 'App\Repositories\Eloquent\EloquentMenuItemRepository'
		);
		
		$this->app->bind(
			'App\Repositories\Contracts\ModuleRepository', 'App\Repositories\Eloquent\EloquentModuleRepository'
		);
    }
}

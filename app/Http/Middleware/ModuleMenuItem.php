<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class ModuleMenuItem
{
	protected $repository;

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\ModuleRepository $repository)
    {
		$this->repository = $repository;
    }
	
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		Log::debug('ModuleMenuItem - Handle');
		$user = $request->user();
		$modulesMenuItem = collect([]);
		
		if (!is_null($request->user())) {
			$modules = $this->repository->all();
			foreach ($modules as $module) {
				if (is_null($module->parent_id)) {
					//Log::debug('ModuleMenuItem Module: '. $module->key);
					foreach ($user->profile->profilesroles as $profileRole) {
						Log::debug('ModuleMenuItem Role: '. $profileRole->role_id);
						if ($profileRole->role_id == $module->role->id) {
							//add de menu item
							$action = action('OpenModuleController@index', ['moduleName' => $module->key]);
							$menuItem = ['text' => $module->name, 'url' => $action];
							$modulesMenuItem->put($module->id, $menuItem);
							Log::debug('ModuleMenuItem add Module: '. $module->key);
							break;
						}
					}
				}
			}
			
			$request->attributes->add(['modulesMenuItem' => $modulesMenuItem]);
		} else {
			Log::debug('ModuleMenuItem User is null');
		}
		
        return $next($request);
    }

}
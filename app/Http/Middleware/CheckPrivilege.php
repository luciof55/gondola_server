<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\ModuleRepository;

class CheckPrivilege
{
	protected $moduleRepository;
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\ModuleRepository $moduleRepository)
    {
		$this->moduleRepository = $moduleRepository;
    }
	
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $resourceKey)
    {
		Log::debug('CheckPrivilege - Handle - Path: '.$request->path());
		Log::debug('CheckPrivilege - Handle - Resource: '.$resourceKey);
        if (!is_null($request->user())) {
			if (!$request->user()->hasResourceAccess($resourceKey)) {
				//Verify is a module
				if ($resourceKey == 'moduleName' || $resourceKey == 'subModuleName') {
					try {
						$moduleKey = $request->route($resourceKey);
						//Log::info('CheckPrivilege - Handle - moduleKey: '.$moduleKey);
						$modules = $this->moduleRepository->findWhere('key', $moduleKey);
						if ($modules->isEmpty()) {
							return redirect('/')->with('unauthorized', "This action is unauthorized or module doesn't exist!");
						} else {
							$user = $request->user();
							$module = $modules[0];
							foreach ($user->profile->profilesroles as $profileRole) {
								//Log::info('ModuleMenuItem Role: '. $profileRole->role_id);
								if ($profileRole->role_id == $module->role->id) {
									return $next($request);
								}
							}
						}
						return redirect('/')->with('unauthorized', "You don't have access to this module!");
					} catch (ModelNotFoundException $e) {
						return redirect('/')->with('unauthorized', "This action is unauthorized or module doesn't exist!");
					}
				} else {
					return redirect('/')->with('unauthorized', 'This action is unauthorized!');
				}
			}
        }
        return $next($request);
    }
}
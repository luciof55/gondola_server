<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class CheckPrivilege
{
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
        if (! $request->user()->hasResourceAccess($resourceKey)) {
			return redirect('/')->with('unauthorized', 'This action is unauthorized!');
        }

        return $next($request);
    }

}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class MenuItem
{

	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\MenuItemRepository $repository)
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
		Log::debug('MenuItem - Handle');
        $this->repository->all();
        return $next($request);
    }

}
<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class SecurityPolicy
{
    use HandlesAuthorization;
	
	/**
     * Determine if the user can enable/disable.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function enable(User $user, $entity)
    {
		Log::debug('SecurityPolicy - enable: '.$entity);
		if (isset($user)) {
			$resourceKey = $entity.'_enable';
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
	/**
     * Determine if the user can remove.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function remove(User $user, $entity)
    {
		if (isset($user)) {
			$resourceKey = $entity.'_remove';
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
	/**
     * Determine if the user can create.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function create(User $user, $entity)
    {
		if (isset($user)) {
			$resourceKey = $entity.'_create';
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
	/**
     * Determine if the user can store.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function store(User $user, $entity)
    {
		if (isset($user)) {
			$resourceKey = $entity.'_store';
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
	/**
     * Determine if the user can edit.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function edit(User $user, $entity)
    {
		if (isset($user)) {
			$resourceKey = $entity.'_edit';
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
	/**
     * Determine if the user can update.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function update(User $user, $entity)
    {
		if (isset($user)) {
			$resourceKey = $entity.'_update';
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
	/**
     * Determine if the user can view.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function view(User $user, $entity)
    {
		if (isset($user)) {
			$resourceKey = $entity.'_view';
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
	/**
     * Determine if the user can show.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function show(User $user, $entity)
    {
		if (isset($user)) {
			$resourceKey = $entity.'_show';
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
	/**
     * Determine if the user can access to the module.
     *
     * @param  \App\User  $user
     * @return bool
     */
    public function module(User $user, $entity)
    {
		if (isset($user)) {
			$resourceKey = $entity;
			return $user->hasResourceAccess($resourceKey);
		} else {
			return false;
		}
    }
	
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}

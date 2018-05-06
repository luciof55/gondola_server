<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use Notifiable;
	use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
	
	/**
     * The attributes uses to sort.
     *
     * @var array
     */
    protected $orderAttributes = ['name'];
	
	/**
     * The attributes uses to filter.
     *
     * @var array
     */
    protected $filterAttributes = ['name'];
	
	public function profile() {
		 return $this->belongsTo('App\Model\Profile')->withTrashed();
	}
	
	public function getResources() {
		$resources = collect([]);
		foreach($this->profile->roles as $role) {
			foreach($role->resources as $resource) {
				Log::debug('User - getResources Adding: '.$resource->key_name);
				$resources->put($resource->key_name, $resource);
			}
		}
		return $resources;
	}
	
	public function hasResourceAccess($resourcekey) {
		Log::debug('User - hasResourceAccess find: '.$resourcekey);
		return $this->getResources()->has($resourcekey);
	}
	
	public function canDelete() {
		$canDelete = true;
		
		if (Auth::guard()->check()) {
			$id = Auth::id();
			$canDelete = ($id != $this->id);
		}
		
		return $canDelete;
	}
	
	public function getOrderAttributes() {
		return $this->orderAttributes;
	}
	
	public function getFilterAttributes() {
		return $this->filterAttributes;
	}
	
	public function isSoftDelete() {
		return true;
	}
}

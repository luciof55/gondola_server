<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Resource extends Model
{
   use Notifiable;
   
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name', 'id', 'key_name'
    ];
	
	/**
     * The attributes uses to sort.
     *
     * @var array
     */
    protected $orderAttributes = ['display_name'];
	
	/**
     * The attributes uses to filter.
     *
     * @var array
     */
    protected $filterAttributes = ['display_name', 'id'];
	
	public function rolespermissions()
    {
        return $this->hasMany('App\Model\RolePermission');
    }
	
	public function menuitems()
    {
        return $this->hasMany('App\Model\MenuItem');
    }
	
	public function roles() {
		return $this->belongsToMany('App\Model\Role', 'roles_permissions');
	}
	
	public function getOrderAttributes() {
		return $this->orderAttributes;
	}
	
	public function getFilterAttributes() {
		return $this->filterAttributes;
	}
	public function isSoftDelete() {
		return false;
	}
	
	public function canDelete() {
		return $this->menuitems->isEmpty();
	}
}

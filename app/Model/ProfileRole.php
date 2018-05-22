<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProfileRole extends Model
{
	protected $table = 'profiles_roles';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['profile_id', 'role_id',];
	
	/**
     * The attributes uses to sort.
     *
     * @var array
     */
    protected $orderAttributes = ['profile_id', 'role_id'];
	
	/**
     * Unique attributes to validate.
     *
     * @var array
     */
    protected $uniqueAttributes = ['profile_id', 'role_id'];
	
	/**
     * The attributes uses to filter.
     *
     * @var array
     */
    protected $filterAttributes = ['profile_id', 'role_id'];
	
	public function profile()
    {
        return $this->belongsTo('App\Model\Profile');
    }
	
	public function role()
    {
        return $this->belongsTo('App\Model\Role');
    }
	
	public function getOrderAttributes() {
		return $this->orderAttributes;
	}
	
	public function getUniqueAttributes() {
		return $this->uniqueAttributes;
	}
	
	public function getFilterAttributes() {
		return $this->filterAttributes;
	}
	public function isSoftDelete() {
		return false;
	}
}
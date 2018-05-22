<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'resource_id', 'id'];
	
	/**
     * The attributes uses to sort.
     *
     * @var array
     */
    protected $orderAttributes = ['role_id', 'resource_id'];
	
	/**
     * Unique attributes to validate.
     *
     * @var array
     */
    protected $uniqueAttributes = ['role_id', 'resource_id'];
	
	/**
     * The attributes uses to filter.
     *
     * @var array
     */
    protected $filterAttributes = ['role_id', 'resource_id',];
	
	public function role()
    {
        return $this->belongsTo('App\Model\Role');
    }
	
	public function resource()
    {
        return $this->belongsTo('App\Model\Resource');
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
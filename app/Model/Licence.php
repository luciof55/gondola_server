<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Licence extends Model
{
	use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'licence', 'id', 'client_id', 'token_id', 'ip', 'hostid', 'licence_amount', 'remoteips'
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
    protected $orderAttributes = ['licence'];
	
	/**
     * The attributes uses to filter.
     *
     * @var array
     */
    protected $filterAttributes = ['licence', 'id'];
	
	public function client() {
		 return $this->belongsTo('Laravel\Passport\Client');
	}
	
	public function token() {
		 return $this->belongsTo('Laravel\Passport\Token');
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
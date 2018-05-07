<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Enumeration\MenuType;

class MenuItem extends Model
{
    use Notifiable;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['resource_id', 'id', 'url', 'type'];
	
	/**
     * The attributes uses to sort.
     *
     * @var array
     */
    protected $orderAttributes = ['resource_id'];
	
	/**
     * The attributes uses to filter.
     *
     * @var array
     */
    protected $filterAttributes = ['resource_id',];
	
	public function resource()
    {
        return $this->belongsTo('App\Model\Resource');
    }
	
	public function getType() {
		$select_types = MenuType::getEnumTranslate();
		return $select_types->get($this->type);
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

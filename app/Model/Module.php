<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use Notifiable;
	use SoftDeletes;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'id', 'key', 'role_id', 'parent_id'];
	
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
    protected $filterAttributes = ['name', 'role_id'];
	
	public function submodules() {
		return $this->hasMany('App\Model\Module', 'parent_id')->withTrashed();
	}
	
	public function role() {
        return $this->belongsTo('App\Model\Role')->withTrashed();
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
		return true;
	}
	
	public function canDelete() {
		return true;
	}
	
	public function delete() {
		if ($this->submodules->isNotEmpty()) {
			foreach($this->submodules as $submodule) {
				$submodule->delete();
			}
		}
		
		parent::delete();
	}
}

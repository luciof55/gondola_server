<?php

namespace App\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use Notifiable;
	use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'id',
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
    protected $filterAttributes = ['name', 'id'];
	
	public function profilesroles()
    {
        return $this->hasMany('App\Model\ProfileRole');
    }
	
	public function roles() {
		return $this->belongsToMany('App\Model\Role', 'profiles_roles');
	}
	
	public function users() {
		$user_class = config('app.user_class');
		return $this->hasMany($user_class);
	}
	
	public function canDelete() {
		return $this->roles->isEmpty() && $this->users->isEmpty();
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
	
	public function delete() {
		if ($this->users->isNotEmpty()) {
			foreach($this->users as $user) {
				$user->delete();
			}
		}
		
		parent::delete();
	}
}

<?php

namespace App\Repositories\Eloquent;

use App\Model\Profile;
use App\Repositories\Contracts\ProfileRepository;

class EloquentProfileRepository extends EloquentBaseRepository implements ProfileRepository
{
    public function entity()
    {
        return Profile::class;
    }
	
	public function getInstance() {
		return new Profile();
	}
	
	protected function softDeleteCascade($profile) {
		$profile->delete();
	}
}

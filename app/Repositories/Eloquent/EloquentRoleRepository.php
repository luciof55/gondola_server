<?php

namespace App\Repositories\Eloquent;

use App\Model\Role;
use App\Repositories\Contracts\RoleRepository;

class EloquentRoleRepository extends EloquentBaseRepository implements RoleRepository
{
    public function entity()
    {
        return Role::class;
    }
	
	public function getInstance() {
		return new Role();
	}
}
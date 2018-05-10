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
	
	protected function softDeleteCascade($role) {
		if ($role->profilesroles->isNotEmpty()) {
			foreach($role->profilesroles as $profileRole) {
				$profileRole->delete();
			}
		}
		
		if ($role->privileges->isNotEmpty()) {
			foreach($role->privileges as $privilege) {
				$privilege->delete();
			}
		}
		
		if ($role->modules->isNotEmpty()) {
			foreach($role->modules as $module) {
				$module->delete();
			}
		}
	}
}
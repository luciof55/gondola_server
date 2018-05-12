<?php

namespace App\Repositories\Eloquent;

use App\Model\Module;
use App\Repositories\Contracts\ModuleRepository;

class EloquentModuleRepository extends EloquentBaseRepository implements ModuleRepository
{
    public function entity()
    {
        return Module::class;
    }
	
	public function getInstance() {
		return new Module();
	}
	
	protected function softDeleteCascade($module) {
		$module->delete();
	}
}
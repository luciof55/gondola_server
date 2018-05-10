<?php

namespace App\Http\Controllers;

use App\Model\Module;
use App\Model\ProfileRole;
use App\Model\Role;
use App\Model\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProfileRoleRepository;
use App\Repositories\Contracts\ProfileRepository;
use App\Repositories\Contracts\RoleRepository;
use App\Rules\ForeignKeyRule;

class ModuleController extends BaseController
{
	protected $profileRepository;
	protected $roleRepository;
	protected $profileRoleRepository;
	
	/**
     * Get a validator for an incoming module request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_create(array $data)
    {
		Log::info('Execute Module create validator.');
        $validator = Validator::make($data, [
            'name' => 'bail|required|string|max:150|unique:modules',
        ]);
		
		$validator->sometimes('parent_id', 'required', function ($input) {
			return is_null($input->profile_id);
		});
		
		$validator->sometimes('profile_id', 'required', function ($input) {
			return is_null($input->parent_id);
		});
		
		return $validator;
    }
	
	/**
     * Get a validator for an incoming role request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_update(array $data)
    {
		Log::info('Execute Module update validator.');
        return Validator::make($data, [
            'name' => 'bail|required|string|max:150|unique:modules,name,'.$data['id'],
        ]);
    }
	
	/**
     * Get a validator for an incoming role request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_delete(array $data)
    {
		Log::info('Execute Module delete validator.');
        Log::info('ID: '.$data['id']);
		return Validator::make($data, [
			'id' => [new ForeignKeyRule($this->repository, $data),],
		]);
    }
	
	/**
     * Add reference data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Support\Collection  $collection
     */
	protected function referenceData(\Illuminate\Http\Request $request, \Illuminate\Support\Collection $collection) {
		$profiles = $this->profileRepository->select(['id', 'name']);
		$roles = $this->roleRepository->select(['id', 'name']);
		$modules = $this->repository->select(['id', 'name', 'parent_id']);
		
		$select_profile = collect([]);
		$select_role = collect([]);
		$select_module = collect([]);
		
		foreach ($profiles as $profile) {
			$select_profile->put($profile->id, $profile->name);
		}
		$collection->put('profiles', $select_profile);
		
		foreach ($roles as $role) {
			$select_role->put($role->id, $role->name);
		}
		$collection->put('roles', $select_module);
		
		foreach ($modules as $module) {
			if (is_null($module->parent_id)) {
				$select_module->put($module->id, $module->name);
			}
		}
		$collection->put('modules', $select_module);
	}
	
	 /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Illuminate\Http\Request $request)
    {	
        try {
			$entity = $this->repository->entity();
			Log::info('Execute '. $entity.' store.');
			$validator = $this->validator_create($request->all());
			if (isset($validator)) {
				$validator->validate();
			}
			
			$data = $request->all();
			$data['key'] = $this->clean($data['name']);
			
			$role = $this->getRole($data['key']);
			
			$data['role_id'] = $role->id;
			
			if (is_null($data['parent_id'])) {
				$profileRole = $this->getProfileRole($role->id, $data['profile_id']);				
			} else {
				$parentModule = $this->repository->find($data['parent_id']);
				foreach ($parentModule->role->profilesroles as $profileRole) {
					$this->getProfileRole($role->id, $profileRole->profile_id);
				}
				if (!is_null($data['profile_id'])) {
					$profileRole = $this->getProfileRole($role->id, $data['profile_id']);
				}
			}	
			
			$this->repository->create($data);
			Log::debug('Module Created');
			return $this->index($request, 'Successfully saved!');
		} catch (Exception $e) {
			Log::debug('Error');
			$collectionStore->put('alertSuccess', 'ERROR');
			$collectionStore->put('command', $this->getCommand($request));
			return redirect()->action($this->getCreateActionName(), $collectionStore->all);
		}
    }
	
	/*
	* Create a ProfileRole a return it, if ProfileRole already exist then return the profileRole found
	* @param role_id
	* @param profile_id
	* @return \App\Model\ProfileRole
	*/
	protected function getProfileRole($role_id, $profile_id) {
		try {
			$profileRoles = $this->profileRoleRepository->findWhere('role_id', $role_id);
			$exist = false;
			foreach ($profileRoles as $profileRole) {
				if ($profileRole->profile->id == $profile_id) {
					Log::debug('Profile Role Exist');
					return $profileRole;
				}
			}
			
			$profileRoleData = ['profile_id' => $profile_id, 'role_id' => $role_id];
			$profileRole = $this->profileRoleRepository->create($profileRoleData);
			Log::debug('Profile Role Created');
			return $profileRole;
		} catch (ModelNotFoundException $e) {
			$profileRoleData = ['profile_id' => $profile_id, 'role_id' => $role_id];
			$profileRole = $this->profileRoleRepository->create($profileRoleData);
			Log::info('Profile Role Created');
			return $profileRole;
		}
	}
	
	/*
	* Create a Role a return it, if Role already exist then return the role found
	* @param role name
	* @return \App\Model\Role
	*/
	protected function getRole($name) {
		Log::debug('-----------------------Role');
		try {
			$roles = $this->roleRepository->findWhere('name', $name);
			if ($roles->isEmpty()) {
				$roleData = ['name' => $name];
				$role = $this->roleRepository->create($roleData);
				Log::debug('Role Created');
			} else {
				Log::debug('Role Exist');
				$role = $roles[0];
			}
		} catch (ModelNotFoundException $e) {
			$roleData = ['name' => $data['key']];
			$role = $this->roleRepository->create($roleData);
			Log::debug('Role Created');
		}
		Log::debug('Role-----------------------');
		
		return $role;
	}
	
	protected function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		return strtolower($string); //return to lower
	}
	
	 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\ModuleRepository $repository, \App\Repositories\Contracts\ProfileRoleRepository $profileRoleRepository, \App\Repositories\Contracts\ProfileRepository $profileRepository, \App\Repositories\Contracts\RoleRepository $roleRepository)
    {
		$this->repository = $repository;
		$this->profileRoleRepository = $profileRoleRepository;
		$this->profileRepository = $profileRepository;
		$this->roleRepository = $roleRepository;
        $this->middleware('auth');
    }
	
	public function getPageSize() {
		return 5;
	}
	
	public function getRouteResource() {
		return 'modules';
	}
	
	public function postProcessCommand(Request $request, $command) {
		$command->name = $request->old('name');
	}
	
	public function postEditProcessCommand(Request $request, $command) {
		if (!empty($request->old('name'))) {
			$command->name = $request->old('name');
		}
	}
	
	public function getControllerName() {
		return 'ModuleController';
	}
	
	public function getEditView() {
		return $this->getViewBase().$this->getRouteResource().'.edit';
	}

}

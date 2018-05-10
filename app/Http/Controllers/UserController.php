<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\ProfileRepository;

class UserController extends BaseController
{
	protected $profileRepository;
	
	/**
     * Get a validator for an incoming user request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_update(array $data)
    {
		Log::info('Execute User update validator.');
        return Validator::make($data, [
            'name' => 'bail|required|string|max:150|unique:users,name,'.$data['id'],
        ]);
    }
	
    public function store(\Illuminate\Http\Request $request)
    {	
		return redirect('/');
    }
	
	 public function create(\Illuminate\Http\Request $request)
    {	
		$collectionCreate = collect([]);
        
		$command = new User();
		
		$page = $request->input('page');
		$actionBack = action('UserController@index');
		
		$filterAttributes = $command->getFilterAttributes();
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, null, $collectionFilterAttributes);
		
		
		$collectionCreate->put('command', $command);
		$collectionCreate->put('actionBack', $actionBack);
		$collectionCreate->put('page', $page);
		$collectionCreate->put('method', 'POST');
		$collectionCreate->put('filters', $collectionFilterAttributes);
		
		$this->referenceData($request, $collectionCreate);
        return view('auth.register', $collectionCreate->all());
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function enable(\Illuminate\Http\Request $request)
    {
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' enable.');
		
		$isSoftDelete = $this->repository->isSoftDelete();
		if ($isSoftDelete) {
			$url = substr($request->url(), 0, strrpos($request->url(), '/'));
			//Log::info('Strip URL: '. $url);
			$id = substr($url, strrpos($url, '/') + 1);
			//Log::info('ID: '. $id);
			if (Auth::guard()->check()) {
				if ($id == Auth::id()) {
					$message = 'Cannot disable yourself!';
					return $this->index($request, $message);
				}
			}
			$validator = $this->validator_enable($request->all());
			if (isset($validator)) {
				$validator->validate();
			}
			$this->repository->updateSoftDelete($id);
			$message = 'Successfully updated!';
		} else {
			$message = 'Enable/Disable feature is not valid.';
		}
		return $this->index($request, $message);
    }
	
	/**
     * Add reference data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Support\Collection  $collection
     */
	protected function referenceData(\Illuminate\Http\Request $request, \Illuminate\Support\Collection $collection) {
		$profiles = $this->profileRepository->select(['id', 'name']);
		
		$select_profile = collect([]);
		foreach ($profiles as $profile) {
			$select_profile->put($profile->id, $profile->name);
		}
		$collection->put('profiles', $select_profile);
		$collection->put('actionCreate', 'register');
	}
	
	 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\UserRepository $repository, \App\Repositories\Contracts\ProfileRepository $profileRepository)
    {
		$this->profileRepository = $profileRepository;
		$this->repository = $repository;
        $this->middleware('auth');
    }
	
	public function getPageSize() {
		return 5;
	}
	
	public function getRouteResource() {
		return 'users';
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
		return 'UserController';
	}

}

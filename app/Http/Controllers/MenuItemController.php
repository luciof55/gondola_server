<?php

namespace App\Http\Controllers;

use App\Model\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\MenuItemRepository;
use App\Repositories\Contracts\ResourceRepository;
use App\Enumeration\MenuType;

class MenuItemController extends BaseController
{
	protected $resourceRepository;
		
	/**
     * Get a validator for an incoming Privilege request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_create(array $data)
    {
		Log::info('Execute MenuItem create validator.');
        return Validator::make($data, [
			'resource_id' => 'bail|required', 
        ]);
    }
	
	/**
     * Add reference data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Support\Collection  $collection
     */
	protected function referenceData(\Illuminate\Http\Request $request, \Illuminate\Support\Collection $collection) {
		$resources = $this->resourceRepository->select(['id', 'display_name']);
		
		$select_resource = collect([]);
		foreach ($resources as $resource) {
			$select_resource->put($resource->id, $resource->display_name);
		}
		$collection->put('resources', $select_resource);
		
		$select_types = MenuType::getEnumTranslate();
		
		$collection->put('types', $select_types);
	}
	
	 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(\App\Repositories\Contracts\MenuItemRepository $repository, \App\Repositories\Contracts\ResourceRepository $resourceRepository)
    {
		$this->repository = $repository;
		$this->resourceRepository = $resourceRepository;
        $this->middleware('auth');
    }
	
	public function getPageSize() {
		return 5;
	}
	
	public function getRouteResource() {
		return 'menuitems';
	}
	
	public function postProcessCommand(\Illuminate\Http\Request $request, $command) {
	}
	
	public function postEditProcessCommand(\Illuminate\Http\Request $request, $command) {
	}
	
	public function getControllerName() {
		return 'MenuItemController';
	}

}

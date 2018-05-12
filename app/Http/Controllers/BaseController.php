<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Rules\ForeignKeyRule;
use App\Rules\RestoreRule;

class BaseController extends Controller
{	
	/**
     * The repository instance.
     */
    protected $repository;
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\Illuminate\Http\Request $request, $message = null)
    {
		$collectionView = collect([]);
		$entity = $this->getRouteResource();
		Log::info('Execute '. $entity.' Index.');
		$actionEdit = action($this->getIndexActionName()).'/|id|/edit/';
		$actionEnable = action($this->getIndexActionName()).'/|id|/enable/';
		$actionDelete = action($this->getIndexActionName()).'/|id|/delete';
		$actionView = action($this->getIndexActionName()).'/|id|';
		$actionCreate = $this->getRouteResource().'.create';
		$collectionView->put('actionEdit', $actionEdit);
		$collectionView->put('actionView', $actionView);
		$collectionView->put('actionEnable', $actionEnable);
		$collectionView->put('actionDelete', $actionDelete);
		$isSoftDelete = $this->repository->isSoftDelete();
		$collectionView->put('isSoftDelete', $isSoftDelete);
		$collectionView->put('entity', $entity);
		$collectionFilterAttributes = collect([]);
		$collectionFilter = collect([]);
		$page = $request->input('page');
		
		$orderAttributes = $this->repository->getInstance()->getOrderAttributes();
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		
		$this->processRequestFilters($request, $filterAttributes, $collectionFilter, $collectionFilterAttributes);

		$collectionView->put('filters', $collectionFilterAttributes);
		
		$totalItems = $this->repository->countWithTrashed($collectionFilter);
		
		if (!empty($page)) {
			$pages = $this->getPages($this->getPageSize(), $totalItems);
			if ($page > $pages) {
				$page = $pages;
			}
		};
		
		$list = $this->repository->paginateWithTrashed(null, $this->getPageSize(), $orderAttributes, $collectionFilter, $page);
		
		$collectionView->put('actionCreate', $actionCreate);
		$collectionView->put('list', $list);
		$collectionView->put('page', $page);
		$collectionView->put('method', 'GET');
		$collectionView->put('action', action($this->getIndexActionName()));
		$collectionView->put('status', $message);
		
		$this->referenceData($request, $collectionView);
		
		Log::info('End Execute, return view '. $this->getIndexView());
		
		return view($this->getIndexView(), $collectionView->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(\Illuminate\Http\Request $request)
    {
		$collectionCreate = collect([]);
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' create.');
        
		$command = $this->getCommand($request);
		$this->postProcessCommand($request, $command);
		
		$action = action($this->getStoreActionName(), $command);
		$page = $request->input('page');
		$actionBack = action($this->getIndexActionName());
		
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, null, $collectionFilterAttributes);
		
		
		$collectionCreate->put('command', $command);
		$collectionCreate->put('actionBack', $actionBack);
		$collectionCreate->put('page', $page);
		$collectionCreate->put('action', $action);
		$collectionCreate->put('method', 'POST');
		$collectionCreate->put('filters', $collectionFilterAttributes);
		
		$this->referenceData($request, $collectionCreate);
		
        return view($this->getCreateView(), $collectionCreate->all());
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
			$this->repository->create($request->all());
			return $this->index($request, 'Successfully saved!');
		} catch (Exception $e) {
			$collectionStore->put('alertSuccess', 'ERROR');
			$collectionStore->put('command', $this->getCommand($request));
			return redirect()->action($this->getCreateActionName(), $collectionStore->all);
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(\Illuminate\Http\Request $request)
    {	
		$collectionShow = collect([]);
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' show.');
		//Log::info('Execute Profile show. '. $request->url());
		$id = substr($request->url(), strrpos($request->url(), '/') + 1);
		Log::info('ID. '. $id);
		$command = $this->repository->find($id);
		$action = action($this->getIndexActionName());
		$page = $request->input('page');
		
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, null, $collectionFilterAttributes);
		
		$collectionShow->put('command', $command);
		$collectionShow->put('page', $page);
		$collectionShow->put('action', $action);
		$collectionShow->put('method', 'POST');
		$collectionShow->put('filters', $collectionFilterAttributes);
		
		$this->referenceData($request, $collectionShow);
		
        return view($this->getShowView(), $collectionShow->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(\Illuminate\Http\Request $request)
    {
		$collectionEdit = collect([]);
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' edit.');
		//Log::info('Execute Profile edit.'. $request->url());
		$url = substr($request->url(), 0, strrpos($request->url(), '/'));
		Log::info('Strip URL: '. $url);
		$id = substr($url, strrpos($url, '/') + 1);
		Log::info('ID: '. $id);
		$command = $this->repository->find($id);
		$this->postEditProcessCommand($request, $command);
		$action = action($this->getUpdateActionName(), $command);
		$page = $request->input('page');
		$actionBack = action($this->getIndexActionName());
		
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, null, $collectionFilterAttributes);
		
		$collectionEdit->put('command', $command);
		$collectionEdit->put('actionBack', $actionBack);
		$collectionEdit->put('page', $page);
		$collectionEdit->put('action', $action);
		$collectionEdit->put('method', 'PUT');
		$collectionEdit->put('filters', $collectionFilterAttributes);
		
		$this->referenceData($request, $collectionEdit);
		
        return view($this->getEditView(), $collectionEdit->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(\Illuminate\Http\Request $request)
    {	
		try {
			$entity = $this->repository->entity();
			Log::info('Execute '. $entity.' update.');
			$validator = $this->validator_update($request->all());
			if (isset($validator)) {
				$validator->validate();
			}
			$id = $request->input('id');
			$this->repository->update($id, $request->all());
			return $this->index($request, 'Successfully updated!');
		} catch (Exception $e) {
			$collectionUpdate->put('alertSuccess', 'ERROR');
			$collectionUpdate->put('command', $this->getCommand($request));
			return redirect()->action($this->getEditActionName(), $collectionUpdate->all());
		}
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
			$validator = $this->validator_enable($request->all());
			if (isset($validator)) {
				$validator->validate();
			}
			$this->repository->updateSoftDelete($id);
			$message = 'Successfully updated!';
		} else {
			$message = null;
		}
		return $this->index($request, $message);
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(\Illuminate\Http\Request $request, $id)
    {
        $entity = $this->repository->entity();
		Log::info('Execute '. $entity.' destroy.');
		$validator = $this->validator_delete($request->all());
		if (isset($validator)) {
			$validator->validate();
		}
		$this->repository->forceDelete($id);
		return $this->index($request, 'Successfully deleted!');
    }
	
	public function getPageSize() {
		return 5;
	}
	
	public function postProcessCommand(\Illuminate\Http\Request $request, $command) {
	}
	
	public function postEditProcessCommand(\Illuminate\Http\Request $request, $command) {
	}
	
	public function getControllerName() {
		return 'BaseController';
	}
	
	public function getIndexView() {
		return $this->getViewBase().$this->getRouteResource().'.index';
	}
	
	public function getCreateView() {
		return $this->getViewBase().$this->getRouteResource().'.form';
	}
	
	public function getEditView() {
		return $this->getViewBase().$this->getRouteResource().'.form';
	}
	
	public function getShowView() {
		return $this->getViewBase().$this->getRouteResource().'.show';
	}
	
	public function getViewBase() {
		return 'admin.';
	}
	
	public function getEditActionName() {
		return $this->getControllerName().'@edit';
	}
	
	public function getUpdateActionName() {
		return $this->getControllerName().'@update';
	}
	
	public function getCreateActionName() {
		return $this->getControllerName().'@create';
	}
	
	public function getStoreActionName() {
		return $this->getControllerName().'@store';
	}
	
	public function getIndexActionName() {
		return $this->getControllerName().'@index';
	}
	
	protected function processRequestFilters($request, $filterAttributes, $collectionFilter, $collectionFilterAttributes) {
		if (!empty($filterAttributes)) {
			Log::info('Hay Filtros');
			foreach ($filterAttributes as $attribute) {
				if (!empty($request->input($attribute.'_filter'))) {
					Log::info('Hay Valor');
					if (isset ($collectionFilter)) {
						$collectionFilter->put($attribute, $request->input($attribute.'_filter'));
					}
				} 
				$collectionFilterAttributes->put($attribute.'_filter', $request->input($attribute.'_filter'));
			}
		}
	}
	
	protected function getPages($pageSize, $totalItems) {
		Log::info('getPages '. ceil($totalItems / $pageSize));
		return ceil($totalItems / $pageSize);
	}
	
	/**
     * Create command object.
     *
     * @param  \Illuminate\Http\Request  $request
     */
	protected function getCommand(\Illuminate\Http\Request $request) {
		return $this->repository->getInstance();
	}
	
	/**
     * Add reference data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Support\Collection  $collection
     */
	protected function referenceData(\Illuminate\Http\Request $request, \Illuminate\Support\Collection $collection) {
		//
	}
	
	/**
     * Get a validator for an incoming Account request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_create(array $data)
    {
    }
	
	/**
     * Get a validator for an incoming account request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_update(array $data)
    {
    }
	
	/**
     * Get a validator for an incoming user request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_delete(array $data)
    {
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' delete validator.');
        Log::info('ID: '.$data['id']);
		return Validator::make($data, [
			'id' => [new ForeignKeyRule($this->repository, $data),],
		]);
    }
	
	/**
     * Get a validator for an incoming account request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_enable($data)
    {
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' enable validator.');
        Log::info('ID: '.$data['id']);
		$command = $this->repository->find($data['id']);
		if ($this->repository->isSoftDelete() && $command->trashed()) {
			return Validator::make($data, [
				'id' => [new RestoreRule($this->repository, $data, $command),],
			]);
		}
    }
}

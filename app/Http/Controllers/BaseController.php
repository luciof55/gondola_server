<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Rules\ForeignKeyRule;
use App\Rules\RestoreRule;
use App\Exports\TableExport;
use \Maatwebsite\Excel\Excel;

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
		
		if (Route::has($this->getRouteGroup().$this->getRouteResource().'.export')) {
			$actionExport = action($this->getExportActionName());
			$collectionView->put('actionExport', $actionExport);
			$collectionView->put('actionExportEnable', true);
		} else {
			$collectionView->put('actionExportEnable', false);	
		}
		
		$actionCreate = $this->getRouteGroup().$this->getRouteResource().'.create';
		$collectionView->put('actionEdit', $actionEdit);
		$collectionView->put('actionView', $actionView);
		$collectionView->put('actionEnable', $actionEnable);
		$collectionView->put('actionDelete', $actionDelete);
		$isSoftDelete = $this->repository->isSoftDelete();
		$collectionView->put('isSoftDelete', $isSoftDelete);
		$collectionView->put('entity', $entity);
		$collectionFilterAttributes = collect([]);
		$collectionFilter = collect([]);
		$collectionOrderAttributes = collect([]);
		$collectionOrder = collect([]);
		$page = $request->input('page');
		
		
		$orderAttributes = $this->repository->getInstance()->getOrderAttributes();
		$this->processRequestOrder($request, $orderAttributes, $collectionOrder, $collectionOrderAttributes);
		
		$columnOrder = $request->input('columnOrder');
		if (empty($columnOrder) && $collectionOrderAttributes->isNotEmpty()) {
			$columnOrder = $collectionOrderAttributes->take(1);
		}
		
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		
		$this->processRequestFilters($request, $filterAttributes, $collectionFilter, $collectionFilterAttributes);

		$collectionView->put('filters', $collectionFilterAttributes);
		$collectionView->put('orders', $collectionOrderAttributes);
		
		$totalItems = $this->repository->countWithTrashed($collectionFilter);
		
		if (!empty($page)) {
			$pages = $this->getPages($this->getPageSize(), $totalItems);
			if ($page > $pages) {
				$page = $pages;
			}
		};
		
		$list = $this->paginateWithTrashed($request, $collectionOrder, $collectionFilter, $page);
		
		$collectionView->put('actionCreate', $actionCreate);
		$collectionView->put('list', $list);
		$collectionView->put('page', $page);
		$collectionView->put('columnOrder', $columnOrder);
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
		$collectionOrderAttributes = collect([]);
		$collectionOrder = collect([]);
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' create.');
        
		$command = $this->getCommand($request);
		$this->postProcessCommand($request, $command);
		
		$action = action($this->getStoreActionName(), $command);
		$page = $request->input('page');
		$actionBack = action($this->getIndexActionName());
		
		$orderAttributes = $this->repository->getInstance()->getOrderAttributes();
		$this->processRequestOrder($request, $orderAttributes, $collectionOrder, $collectionOrderAttributes);
		
		$columnOrder = $request->input('columnOrder');
		if (empty($columnOrder) && $collectionOrderAttributes->isNotEmpty()) {
			$columnOrder = $collectionOrderAttributes->take(1);
		}
		
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, null, $collectionFilterAttributes);
		
		$collectionCreate->put('command', $command);
		$collectionCreate->put('actionBack', $actionBack);
		$collectionCreate->put('page', $page);
		$collectionCreate->put('action', $action);
		$collectionCreate->put('method', 'POST');
		$collectionCreate->put('filters', $collectionFilterAttributes);
		$collectionCreate->put('orders', $collectionOrderAttributes);
		$collectionCreate->put('statusError', $request->session()->pull('statusError'));
		
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
        // try {
			$entity = $this->repository->entity();
			Log::info('Execute '. $entity.' store.');
			$validator = $this->validator_create($this->getValidateStoreData($request));
			if (isset($validator)) {
				$validator->validate();
			}
			$command = $this->storeCommand($request);
			$this->fireCreateEvent($request, $command);
			return $this->index($request, 'Successfully saved!');
		// } catch (\Exception $e) {
			// Log::error($e);
			// $request->session()->put('statusError', 'Se ha producido un error');
			// return $this->create($request);
		// }
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
		$collectionCreate = collect([]);
		$collectionOrderAttributes = collect([]);
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' show.');
		//Log::info('Execute Profile show. '. $request->url());
		$id = substr($request->url(), strrpos($request->url(), '/') + 1);
		//Log::info('ID. '. $id);
		try {
			$command = $this->repository->find($id);
		} catch (\Exception $e) {
			$request->session()->flash('statusError', 'Acceso no válido');
			return view('error');
		}
		$action = action($this->getIndexActionName());
		$page = $request->input('page');
		
		$orderAttributes = $this->repository->getInstance()->getOrderAttributes();
		$this->processRequestOrder($request, $orderAttributes, null, $collectionOrderAttributes);
		
		$columnOrder = $request->input('columnOrder');
		if (empty($columnOrder) && $collectionOrderAttributes->isNotEmpty()) {
			$columnOrder = $collectionOrderAttributes->take(1);
		}
		
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, null, $collectionFilterAttributes);
		
		$collectionShow->put('command', $command);
		$collectionShow->put('page', $page);
		$collectionShow->put('action', $action);
		$collectionShow->put('method', 'POST');
		$collectionShow->put('filters', $collectionFilterAttributes);
		$collectionShow->put('orders', $collectionOrderAttributes);
		
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
		$collectionCreate = collect([]);
		$collectionOrderAttributes = collect([]);
		$collectionOrder = collect([]);
		$entity = $this->repository->entity();
		Log::info('Execute '. $entity.' edit.');
		//Log::info('Execute Profile edit.'. $request->url());
		$url = substr($request->url(), 0, strrpos($request->url(), '/'));
		//Log::info('Strip URL: '. $url);
		$id = substr($url, strrpos($url, '/') + 1);
		//Log::info('ID: '. $id);
		$command = $this->getCommand($request, $id);
		$this->postEditProcessCommand($request, $command);
		$action = action($this->getUpdateActionName(), $command);
		$page = $request->input('page');
		$actionBack = action($this->getIndexActionName());
		
		$orderAttributes = $this->repository->getInstance()->getOrderAttributes();
		$this->processRequestOrder($request, $orderAttributes, $collectionOrder, $collectionOrderAttributes);
		
		$columnOrder = $request->input('columnOrder');
		if (empty($columnOrder) && $collectionOrderAttributes->isNotEmpty()) {
			$columnOrder = $collectionOrderAttributes->take(1);
		}
		
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, null, $collectionFilterAttributes);
		
		$collectionEdit->put('command', $command);
		$collectionEdit->put('actionBack', $actionBack);
		$collectionEdit->put('page', $page);
		$collectionEdit->put('action', $action);
		$collectionEdit->put('method', 'PUT');
		$collectionEdit->put('filters', $collectionFilterAttributes);
		$collectionEdit->put('orders', $collectionOrderAttributes);
		$collectionEdit->put('statusError', $request->session()->pull('statusError'));
		
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
		// try {
			$entity = $this->repository->entity();
			Log::info('Execute '. $entity.' update.');
			$validator = $this->validator_update($this->getValidateUpdateData($request));
			if (isset($validator)) {
				$validator->validate();
			}
			$id = $request->input('id');
			$command = $this->updateCommand($request, $id);
			$this->fireUpdateEvent($request, $command);
			return $this->index($request, 'Successfully updated!');
		// } catch (\Exception $e) {
			// Log::error($e);
			// $request->session()->put('statusError', 'Se ha producido un error');
			// return $this->edit($request);
		// }
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
		$command = $this->repository->find($id);
		$this->repository->forceDelete($id);
		$this->fireDeleteEvent($request, $command);
		return $this->index($request, 'Successfully deleted!');
    }
	
	public function export(\Illuminate\Http\Request $request, \Maatwebsite\Excel\Excel $excel) {
		$orderAttributes = $this->repository->getInstance()->getOrderAttributes();
		$filterAttributes = $this->repository->getInstance()->getFilterAttributes();
		$collectionFilter = collect([]);
		$collectionOrder = collect([]);
		$collectionFilterAttributes = collect([]);
		$collectionOrderAttributes = collect([]);
		
		$this->processRequestOrder($request, $orderAttributes, $collectionOrder, $collectionOrderAttributes);
		
		$collectionFilterAttributes = collect([]);
		$this->processRequestFilters($request, $filterAttributes, $collectionFilter, $collectionFilterAttributes);
		
		$list = $this->repository->paginateWithTrashed(null, null, $collectionOrder, $collectionFilter, null);
		$export = new TableExport($list);
		return $excel->download($export, 'list.xlsx');
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
	
	public function getRouteGroup() {
		return 'security.';
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
	
	public function getExportActionName() {
		return $this->getControllerName().'@export';
	}
	
	protected function fireUpdateEvent(\Illuminate\Http\Request $request, $command) {
	}
	
	protected function fireCreateEvent(\Illuminate\Http\Request $request, $command) {
	}
	
	protected function fireDeleteEvent(\Illuminate\Http\Request $request, $command) {
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
	
	protected function processRequestOrder($request, $orderAttributes, $collectionOrder, $collectionOrderAttributes) {
		if (!empty($orderAttributes)) {
			Log::info('Hay Orden');
			if (!empty($request->input('columnOrder')) && !empty($request->input($request->input('columnOrder')))) {
				if (isset ($collectionOrder) ) {
					$aux = explode('_', $request->input('columnOrder'));
					$collectionOrder->put($aux[0], 'asc');
				}
			}
			
			foreach ($orderAttributes as $attribute) {
				if (!empty($request->input($attribute.'_order'))) {
					Log::info('Hay Valor');
					if (isset ($collectionOrder)) {
						$collectionOrder->put($attribute, $request->input($attribute.'_order'));
					}
				} 
				$collectionOrderAttributes->put($attribute.'_order', $request->input($attribute.'_order'));
			}

		}
	}
	
	protected function getPages($pageSize, $totalItems) {
		Log::info('getPages '. ceil($totalItems / $pageSize));
		return ceil($totalItems / $pageSize);
	}
	
	protected function getItemPage($pageSize, $items, $itemKey) {
		$value = $items->get($itemKey);
		if (isset($value)) {
			// Log::info('+++++++++++++++++++');
			// Log::info($value);
			$position = $items->values()->search($value);
			// Log::info('position '. $position);
			// Log::info('+++++++++++++++++++');
			return (intdiv($position, $pageSize) + 1);
		} else {
			return 0;
		}
		
	}
	
	protected function getItemsPages($items, $pageSize, $page) {
		if ($items->isNotEmpty()) {
			$chunks = $items->chunk($pageSize);
			$slice = $chunks[$page];
			return $slice;
		} else {
			return collect([]);
		}
	}
	
	/**
     * Create command object.
     *
     * @param  \Illuminate\Http\Request  $request
     */
	protected function getCommand(\Illuminate\Http\Request $request, $id = null) {
		if (!blank($this->getCommandKey()) && $request->session()->has($this->getCommandKey())) {
			$command = $request->session()->get($this->getCommandKey(), null);
			if (isset($command)) {
				// Log::info('Command-----------');
				// Log::info($command);
				// Log::info('-----------Command');
				return $command;
			}
		} 
		if (is_null($id)) {
			return $this->repository->getInstance();
		} else {
			return $this->repository->find($id);
		}
	}
	
	protected function storeCommand(\Illuminate\Http\Request $request) {
		return $this->repository->create($request->all());
	}
	
	protected function updateCommand(\Illuminate\Http\Request $request, $id) {
		$this->repository->update($id, $request->all());
		return $this->repository->find($id);
	}
	
	protected function getCommandKey() {
		return '';
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
	
	protected function paginateWithTrashed(\Illuminate\Http\Request $request, $collectionOrder, $collectionFilter, $page) {
		return $this->repository->paginateWithTrashed(null, $this->getPageSize(), $collectionOrder, $collectionFilter, $page);
	}
	
	/**
	* Replace spaces with "-" and remove special chars
	*
	* @param $string: string to clean
	* @return: string clean and to lower case
	*
	*/
	protected function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		return strtolower($string); //return to lower
	}
	
	protected function getValidateStoreData(\Illuminate\Http\Request $request) {
		return $request->all();
	}
	
	/**
     * Get a validator for an incoming Account request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_create(array $data){}
	
	protected function getValidateUpdateData(\Illuminate\Http\Request $request) {
		return $request->all();
	}
	
	/**
     * Get a validator for an incoming account request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator_update(array $data){}
	
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

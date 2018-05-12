<?php

namespace App\Repositories\Eloquent;

use Kurt\Repoist\Repositories\Eloquent\AbstractRepository;
use Illuminate\Support\Facades\Log;

class EloquentBaseRepository extends AbstractRepository
{
	public function canDelete($command) {
		$result = collect([]);
		$result->put('message', null);
		$result->put('status', true);
		
		if (method_exists($command, 'canDelete')) {
			$result->put('status', $command->canDelete());	
		} else {
			$result->put('status', true);	
		}
		
		return $result;
	}
	
	public function canSoftDelete($command) {
		$result = collect([]);
		$result->put('message', null);
		$result->put('status', true);
		
		if (method_exists($command, 'canSoftDelete')) {
			$result->put('status', $command->canSoftDelete());	
		} else {
			$result->put('status', true);	
		}
		
		return $result;
	}
	
	public function canRestore($command) {
		$result = collect([]);
		$result->put('message', null);
		
		if (method_exists($command, 'canRestore')) {
			$result->put('status', $command->canRestore());	
		} else {
			$result->put('status', true);	
		}
		
		return $result;
	}

	public function select($array_select, $paginate = null) {
		Log::info('Function all.');
		$query = $this->entity->select($array_select);
		return $paginate ? $query->paginate($paginate) : $query->get();
	}
	
	public function paginateWithTrashed($query = null, $paginate = null, $orderAttributes = null, $filterAttributes = null, $page = null)
    {
		if (isset($query)) {
			return $this->getWithTrashed($query, $paginate, $orderAttributes, $filterAttributes, $page);
		} else {
			if ($this->isSoftDelete()) {
				$query = $this->entity->withTrashed();
			} else {
				$query = $this->entity;
			}
			return $this->getWithTrashed($query, $paginate, $orderAttributes, $filterAttributes, $page);
		}
	}
	
	/**
     * @param $id
     * @return mixed
     */
    public function findWithTrashed($id)
    {
		$model = $this->entity->withTrashed()->find($id);

        if (!$model) {
            throw (new ModelNotFoundException)->setModel(
                get_class($this->entity->getModel()),
                $id
            );
        }

        return $model;
    }
	
	/**
     * @param $id
     * @return mixed
     */
    public function forceDelete($id)
    {
        return $this->find($id)->forceDelete();
    }
	
	public function countWithTrashed($filterAttributes = null) {
	
		$query = $this->entity;
		
		if ($this->isSoftDelete()) {
			$query = $query->withTrashed();
		}
		
		if (isset($filterAttributes) && $filterAttributes->isNotEmpty()) {
			Log::info('Hay Filtros-Valor');
			foreach ($filterAttributes->keys() as $attributeKey) {
				Log::info('Key: '.$attributeKey);
				$value = $filterAttributes->get($attributeKey);
				Log::info('value:'.$value.'FIN');
				if(is_numeric($value)) {
					Log::info('Adding NUMBER-------------------------- ');
					$query = $query->where($attributeKey, $value);
				} else {
					$query = $query->where($attributeKey, 'like', '%'.$value.'%');
				}
			}
			// print_r($query->getBindings());
			// Log::info('IF SQL: '.$query->toSql());
			// Log::info('IF SQL: '.$query->toBase()->count());
			return $query->count();
		}
		return $query->count();
	}
	
	/**
     * @param $id
     * @return mixed
     */
    public function find($id) {
		Log::info('Find.');
		if ($this->isSoftDelete()) {
			return $this->findWithTrashed($id);
		} else {
			return parent::find($id);
		}
	}
	
	/**
     * @param $id
     * @return mixed
     */
    public function isSoftDelete() {
		return $this->entity->isSoftDelete();
	}
	
	public function updateSoftDelete($id) {
		if ($this->isSoftDelete()) {
			$command = $this->find($id);
			if ($command->trashed()) {
				$result = $this->canRestore($command);
				if ($result['status']) {
					$command->restore();
					Log::info('Command Restore.');
				}
			} else {
				$this->softDeleteCascade($command);
				Log::info('Command Deleted.');
			}
		}
	}
	
	protected function softDeleteCascade($command) {
		$command->delete();
	}
	
	protected function addNestedFilters($query, $filterAttributes) {
	}
	
     /**
     * @return mixed
     */
    protected function getWithTrashed($query, $paginate = null, $orderAttributes = null, $filterAttributes = null, $page = null)
    {
		if (isset($filterAttributes) && $filterAttributes->isNotEmpty()) {
			Log::info('Hay Filtros-Valor');
			foreach ($filterAttributes->keys() as $attributeKey) {
				//if ($attributeKey.substr('.', 0) == -1) {
					Log::info('Key: '.$attributeKey);
					$value = $filterAttributes->get($attributeKey);
					Log::info('value: '.$value);
					if(is_numeric($value)) {
						Log::info('Adding NUMBER-------------------------- ');
						$query->where($attributeKey, $value);
					} else {
						$query->where($attributeKey, 'like', '%'.$value.'%');
					}
				//}
			}
			$this->addNestedFilters($query, $filterAttributes);
		};
		
		if (!empty($orderAttributes)) {
			foreach ($orderAttributes as $attribute) {
				$query->orderBy($attribute);
			}
		};
			
		return $paginate ? $query->paginate($paginate, null, null, $page) : $query->get();
	}
}

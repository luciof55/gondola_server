<?php

namespace App\Repositories\Contracts;

interface BaseRepository
{
	 public function select($array_select, $paginate = null);
	 
	 public function paginateWithTrashed($query = null, $paginate = null, $orderAttributes = null, $filterAttributes = null, $page = null);
	 
	 public function findWithTrashed($id);
	 
	 public function forceDelete($id);
	 
	 public function countWithTrashed();
	 
	 public function find($id);
	 
	 public function isSoftDelete();
	  
	 public function updateSoftDelete($id);
	 
	 public function canDelete($command);
	 
	 public function canRestore($command);
	 
	 public function canSoftDelete($command);
	 
	 public function getRelation($relationName);
}

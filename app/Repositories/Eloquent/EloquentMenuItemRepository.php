<?php

namespace App\Repositories\Eloquent;

use App\Model\MenuItem;
use App\Repositories\Contracts\MenuItemRepository;
use Illuminate\Support\Facades\Log;

class EloquentMenuItemRepository extends EloquentBaseRepository implements MenuItemRepository
{
    public function entity()
    {
        return MenuItem::class;
    }
	
	public function getInstance() {
		return new MenuItem();
	}
	
	public function paginateWithTrashed($query = null, $paginate = null, $orderAttributes = null, $filterAttributes = null, $page = null)
    {
		Log::info('EloquentMenutItemRepository -> paginateWithTrashed.');
		$query = $this->entity->with(['resource']);
		return $this->getWithTrashed($query, $paginate, $orderAttributes, $filterAttributes, $page);
	}
}
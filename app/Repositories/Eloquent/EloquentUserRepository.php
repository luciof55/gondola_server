<?php

namespace App\Repositories\Eloquent;

use App\User;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepository
{
    public function entity()
    {
        return User::class;
    }
	
	public function getInstance() {
		return new User();
	}
	
	public function updateSoftDelete($id) {
		
		if (Auth::guard()->check()) {
			$user_id = Auth::id();
			if ($user_id == $id) {
				return;
			}
		}
		
		return parent::updateSoftDelete($id);
	}
	
	public function canRestore($command) {
		Log::info('EloquentUserRepository - canRestore');
		$result = collect([]);
		$result->put('message', null);
		$result->put('status', true);
		
		if (method_exists($command, 'canRestore')) {
			$result->put('status', $command->canRestore());
		}
		
		if ($result->get('status') && $command->profile->trashed()) {
			$result->put('message', "El perfil del usuario está deshabilitado. Cambie de perfil o habilite el perfil: '".$command->profile->name."'");
			$result->put('status', false);
		}
		
		return $result;
	}
}
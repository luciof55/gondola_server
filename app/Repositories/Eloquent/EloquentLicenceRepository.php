<?php

namespace App\Repositories\Eloquent;

use App\Model\Licence;
use App\Repositories\Contracts\LicenceRepository;

class EloquentLicenceRepository extends EloquentBaseRepository implements LicenceRepository
{
    public function entity()
    {
        return Licence::class;
    }
	
	public function getInstance() {
		return new Licence();
	}
	
	protected function softDeleteCascade($license) {
		$license->delete();
	}
}

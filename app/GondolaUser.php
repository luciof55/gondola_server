<?php

namespace App;

use Laravel\Passport\HasApiTokens;

class GondolaUser extends User
{
	use HasApiTokens;
	
	protected $table = 'users';
}

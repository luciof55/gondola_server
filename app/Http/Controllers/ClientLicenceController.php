<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ClientLicenceController extends Controller
{
	
	public function __construct() {
		$this->middleware('auth');
	}
	
    public function clients(\Illuminate\Http\Request $request)
    {
		return view('clients');
		
    }
	
	public function tokens(\Illuminate\Http\Request $request)
    {
		return view('tokens');
		
    }
}

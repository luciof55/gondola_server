<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\ClientRepository;
use Carbon\Carbon;

class TokenController extends Controller
{
	protected $tokenRepository;
	
	protected $clientRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ClientRepository $clientRepository)
    {
		$this->clientRepository = $clientRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getToken(\Illuminate\Http\Request $request)
    {
		Log::info('GetToken from: '.$request->ip());
		Log::info('GetToken client: '.$request['client_id']);
		Log::info('GetToken client_secret: '.$request['client_secret']);
		Log::info('GetToken client_user: '.$request['client_user']);
		
		$client = $this->clientRepository->find($request['client_id']);
		
		Log::info($client);
		
		$token = $client->tokens()
                      ->whereRevoked(0)
					  ->where('user_id', '=', $request['client_user'])
                      ->where('expires_at', '>', Carbon::now())
                      ->latest('expires_at')
                      ->first();
		
		Log::info($token);
		
		if ($token) {
			return response()->json($token->id, 200);
		} else {
			return response()->json('', 200);
		}
		
    }
}

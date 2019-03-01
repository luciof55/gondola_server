<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;
use League\OAuth2\Server\ResourceServer;
use Carbon\Carbon;
use Exception;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use App\Services\LicenceServiceInterface;

class TokenController extends Controller
{
	protected $tokenRepository;
	protected $licenceService;
	protected $clientRepository;
	
	/**
     * The resource server instance.
     *
     * @var \League\OAuth2\Server\ResourceServer
     */
    protected $server;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ResourceServer $server, ClientRepository $clientRepository, \App\Services\LicenceServiceInterface $licenceService)
    {
		$this->server = $server;
		$this->clientRepository = $clientRepository;
		$this->licenceService = $licenceService;
		Log::debug('TokenController - __construct');
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
		Log::info('GetToken client_licence: '.$request['client_licence']);
		Log::info('GetToken remoteIp: '.$request['remoteIp']);
		Log::info('GetToken hostname: '.$request['hostname']);
		Log::info('GetToken macAddress: '.$request['macAddress']);
		
		$client = $this->clientRepository->find($request['client_id']);
		
		Log::info('GetToken CLIENT');
		Log::info($client);
		
		if ($client && !$client->revoked) {
			$token = $client->tokens()
						  ->whereRevoked(0)
						  ->where('user_id', '=', $request['client_user'])
						  ->where('expires_at', '>', Carbon::now())
						  ->latest('expires_at')
						  ->first();
			
			Log::info($token);
			
			if ($token) {
				try {
					if ($this->validateLicenceWithToken($request->ip(), $request['remoteIp'], $request['hostname'], $request['macAddress'],$request['client_licence'], $token)) {
						return response()->json('OK', 200);
					} else {
						return response()->json('', 200);
					}
				} catch (Exception $e) {
					Log::error($e);
					return response()->json('', 200);
				}
				
			} else {
				return response()->json('', 200);
			}
		} else {
			return response()->json('', 200);
		}
		
    }
	
	protected function validateLicenceWithToken($ip, $remoteIp, $hostname, $macAddress, $client_licence, $token) {
	
		return $this->licenceService->validateLicenceWithToken($ip, $remoteIp, $hostname, $macAddress, $client_licence, $token);
		
	}
}

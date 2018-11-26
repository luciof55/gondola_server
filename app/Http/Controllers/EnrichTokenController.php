<?php

namespace App\Http\Controllers;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ServerRequestInterface;
use App\Repositories\Contracts\LicenceRepository;
use Laravel\Passport\ClientRepository;
use Carbon\Carbon;
use App\Services\LicenceServiceInterface;


class EnrichTokenController extends AccessTokenController
{
	protected $licenceRepository;
	protected $clientRepository;
	protected $tokenRepository;
	protected $licenceService;
	
	/**
     * Create a new controller instance.
     *
     * @param  \League\OAuth2\Server\AuthorizationServer  $server
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @param  \Lcobucci\JWT\Parser  $jwt
     * @return void
     */
    public function __construct(\League\OAuth2\Server\AuthorizationServer $server, \Laravel\Passport\TokenRepository $tokens, \Lcobucci\JWT\Parser $jwt, \App\Repositories\Contracts\LicenceRepository $licenceRepository, \Laravel\Passport\ClientRepository $clientRepository, \App\Services\LicenceServiceInterface $licenceService)
    {
        $this->licenceRepository = $licenceRepository;
		$this->clientRepository = $clientRepository;
		$this->tokenRepository = $tokens;
		$this->licenceService = $licenceService;
		parent::__construct($server, $tokens, $jwt);
    }

	
	/**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @return \Illuminate\Http\Response
     */
    public function issueToken(ServerRequestInterface $request)
    {
		Log::info('IssueToken START');
		
		if ($this->validateClient($request)) {
			
			if ($this->validateLicence($request)) {
				
				Log::info($request->getServerParams());
			
				$response = parent::issueToken($request);
			
				if ($response->getStatusCode() == 200) {
					//Access token generated, update it!
					Log::info($response->getContent());
					$token = $this->getToken($request);
					if ($token) {
						$ip = $request->getServerParams()['REMOTE_ADDR'];
						$client_licence = $this->getRequestParameter('client_licence', $request, null);
						Log::info('issueToken client_licence: '.$client_licence);
						Log::info('issueToken hostname: '. $this->getRequestParameter('hostname', $request, null));
						$hostname = $this->getRequestParameter('hostname', $request, null);
						$this->licenceService->updateLicence($token, $ip, $hostname, $client_licence);
					}
				}
			
				return $response;
				
			} else {
				$result = json_encode(['message' => 'ERROR - Licencia no valida']);
				return response()->json($result, 200);
			}
		
		} else {
			$result = json_encode(['message' => 'ERROR - Cliente no valido']);
			return response()->json($result, 200);
		}
    }
	
	protected function getToken($request) {
		$client_id = $this->getRequestParameter('client_id', $request, null);
		Log::info('GetToken client: '.$client_id);
		
		$client_secret = $this->getRequestParameter('client_secret', $request, null);
		Log::info('GetToken client_secret: '.$client_secret);
		
		$client_user = $this->getRequestParameter('client_user', $request, null);
		Log::info('GetToken client_user: '.$client_user);
		
		return $this->licenceService->getToken($client_id, $client_secret, $client_user);
	}
	
	protected function updateLicence($request, $token) {
		$client_licence = $this->getRequestParameter('client_licence', $request, null);
		Log::info('updateLicence client_licence: '.$client_licence);
		
		$ip = $request->getServerParams()['REMOTE_ADDR'];
		Log::info('updateLicence from: '.$ip);
		
		return $this->licenceService->updateLicence($token, $ip, $client_licence);
	}
	
	protected function validateLicence($request) {
		$client_licence = $this->getRequestParameter('client_licence', $request, null);
		Log::info('validateLicence client_licence: '.$client_licence);
		
		$ip = $request->getServerParams()['REMOTE_ADDR'];
		Log::info('validateLicence from: '.$ip);
		
		return $this->licenceService->validateLicence($ip, $client_licence);
		
	}
	
	protected function validateClient($request) {
		
		$client_id = $this->getRequestParameter('client_id', $request, null);
		Log::info('validateClient client id: '.$client_id);
		
		$client_secret = $this->getRequestParameter('client_secret', $request, null);
		Log::info('validateClient client secret: '.$client_secret);
		
		return $this->licenceService->validateClient($client_id, $client_secret);
		
	}
	
	/**
     * Retrieve request parameter.
     *
     * @param string                 $parameter
     * @param ServerRequestInterface $request
     * @param mixed                  $default
     *
     * @return null|string
     */
    protected function getRequestParameter($parameter, ServerRequestInterface $request, $default = null)
    {
        $requestParameters = (array) $request->getParsedBody();

        return isset($requestParameters[$parameter]) ? $requestParameters[$parameter] : $default;
    }
}

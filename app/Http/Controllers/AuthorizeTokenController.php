<?php

namespace App\Http\Controllers;

use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;
use Carbon\Carbon;
use App\Repositories\Contracts\LicenceRepository;
use App\Services\LicenceServiceInterface;

class AuthorizeTokenController extends AuthorizationController
{
	protected $licenceRepository;
	protected $licenceService;
	
	/**
     * Create a new controller instance.
     *
     * @param  \League\OAuth2\Server\AuthorizationServer  $server
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @param  \Lcobucci\JWT\Parser  $jwt
     * @return void
     */
    public function __construct(\League\OAuth2\Server\AuthorizationServer $server, \Illuminate\Contracts\Routing\ResponseFactory $response, \App\Repositories\Contracts\LicenceRepository $licenceRepository,  \App\Services\LicenceServiceInterface $licenceService)
    {
        $this->licenceRepository = $licenceRepository;
		$this->licenceService = $licenceService;
		parent::__construct($server, $response);
    }

	
	/**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $psrRequest
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @return \Illuminate\Http\Response
     */
    public function authorize(ServerRequestInterface $psrRequest,
                              Request $request,
                              ClientRepository $clients,
                              TokenRepository $tokens)
    {
		Log::info('authorize START');
		
		if ($this->validateClient($psrRequest)) {
			
			if ($this->validateLicence($psrRequest)) {
				
				Log::info($psrRequest->getServerParams());
			
				return parent::authorize($psrRequest, $request, $clients, $tokens);
				
			} else {
				$redirect_licence_error = $this->getRequestParameter('redirect_licence_error', $psrRequest, null);
				Log::info('authorize redirect_licence_error: '.$redirect_licence_error);
				
				return redirect($redirect_licence_error.'?message=LICENCE_ERROR');
			}
		
		} else {
			$redirect_licence_error = $this->getRequestParameter('redirect_licence_error', $psrRequest, null);
			Log::info('authorize redirect_licence_error: '.$redirect_licence_error);
			
			return redirect($redirect_licence_error.'?message=CLIENT_ERROR');
		}
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\Http\Controllers\AuthorizedAccessTokenController;
use Laravel\Passport\Token;
use App\Services\LicenceServiceInterface;
use App\Model\Licence;
use Illuminate\Support\Facades\Log;

class AuthorizedClientsTokenController extends AuthorizedAccessTokenController
{
	
	protected $licenceService;
	
	 /**
     * Create a new controller instance.
     *
     * @param  \Laravel\Passport\TokenRepository  $tokenRepository
     * @return void
     */
    public function __construct(TokenRepository $tokenRepository, \App\Services\LicenceServiceInterface $licenceService)
    {
        $this->licenceService = $licenceService;
		parent::__construct($tokenRepository);
    }

    /**
     * Get all of the authorized tokens for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forAllUser(Request $request)
    {
        
		$licences = $this->licenceService->all();
		
		Log::info($licences);

        $values =  $licences->load('client', 'token')->filter(function ($token) {
            return ! $token->client->firstParty() && ! $token->revoked;
        })->values();
		
		
		Log::info($values);
		
		return $values;
    }
	
	
	/**
     * Add a Licence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addLicence(Request $request)
    {
		Log::info('addLicence');
		
		Log::info($request);
		
		$licence = $this->licenceService->createLicence($request['client'], $request['licence'], $request['amount']);
		
		if ($licence) {
			$result = json_encode(['message' => 'OK', 'licence' => $licence]);
		} else {
			$result = json_encode(['message' => 'Error al crear la licencia']);
		}
		
		return response()->json($result, 200);
    }
	
	/**
     * Rovoke the given token and release the licence associeted.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $tokenId)
    {
		if ($this->licenceService->releaseLicence($tokenId)) {
			new Response('OK', 200);
		} else {
			 new Response('', 404);
		}
    }
	
	/**
     * Delete the given licence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\Response
     */
    public function deleteLicence(Request $request, $licenceId)
    {
       if ($this->licenceService->deleteLicence($licenceId)) {
			new Response('OK', 200);
		} else {
			 new Response('', 404);
		}
    }

	/**
     * Enable the given licence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\Response
     */
    public function enableLicence(Request $request, $licenceId)
    {
       if ($this->licenceService->enableLicence($licenceId)) {
			new Response('OK', 200);
		} else {
			 new Response('', 404);
		}
    }
}

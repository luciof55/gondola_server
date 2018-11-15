<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\LicenceRepository;
use Laravel\Passport\ClientRepository;
use Carbon\Carbon;

class LicenceService implements LicenceServiceInterface
{
	protected $licenceRepository;
	protected $clientRepository;
	protected $tokenRepository;
	protected $server;
	
	/**
     * Create a new controller instance.
     *
     * @param  \League\OAuth2\Server\AuthorizationServer  $server
     * @param  \Laravel\Passport\TokenRepository  $tokens
     * @param  \Lcobucci\JWT\Parser  $jwt
     * @return void
     */
    public function __construct(\League\OAuth2\Server\AuthorizationServer $server, \Laravel\Passport\TokenRepository $tokens, \App\Repositories\Contracts\LicenceRepository $licenceRepository, \Laravel\Passport\ClientRepository $clientRepository)
    {
        $this->licenceRepository = $licenceRepository;
		$this->clientRepository = $clientRepository;
		$this->tokenRepository = $tokens;
		$this->server = $server;
    }

	public function getToken($client_id, $client_secret, $client_user) {
		
		$client = $this->clientRepository->find($client_id);
		
		Log::info($client);
		
		if ($client && $client->secret == $client_secret) {
		
			$token = $client->tokens()
						  ->whereRevoked(0)
						  ->where('user_id', '=', $client_user)
						  ->where('expires_at', '>', Carbon::now())
						  ->latest('expires_at')
						  ->first();
			
			Log::info($token);
		
			return $token;
			
		} else {
			return null;
		}
	}
	
	public function updateLicence($token, $ip, $client_licence) {
		Log::info('LicenceService - updateLicence START');
		
		if (isset($token) && isset($ip) && isset($client_licence)) {
			
			$licence = $this->licenceRepository->findWhereFirst('licence', $client_licence);
			if ($licence) {
				$licence->ip = $ip;
				$licence->token_id = $token->id;
				$licence->save();
				Log::info('updateLicence client licence updated: '.$client_licence);
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function validateLicence($ip, $client_licence) {
		Log::info('LicenceService - validateLicence START');
		
		if (isset($ip) && isset($client_licence)) {
			
			$licence = $this->licenceRepository->findWhereFirst('licence', $client_licence);
			
			if ($licence) {
				if (!isset($licence->ip) && !isset($licence->token)) {
					return true;
				} else {
					Log::info('validateLicence from: '.$ip);
					if ($ip == $licence->ip) {
						return true;
					} else {
						Log::info('validateLicence not valid IP: '.$ip.' expected: '.$licence->ip);
						//IP is difernet...
						return false;
					}
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function validateLicenceWithToken($ip, $client_licence, $token) {
		Log::info('LicenceService - validateLicenceWithToken START');
		
		if (isset($token) && isset($ip) && isset($client_licence)) {
			
			$licence = $this->licenceRepository->findWhereFirst('licence', $client_licence);
			
			if ($licence) {
				if (!isset($licence->ip) || !isset($licence->token)) {
					Log::info('validateLicenceWithToken no IP or Token activated');
					return false;
				} else {
					if ($ip == $licence->ip && $token->id == $licence->token->id) {
						return true;
					} else {
						Log::info('validateLicenceWithToken recived IP: '.$ip.' expected: '.$licence->ip);
						Log::info('validateLicenceWithToken recived token: '.$token->id.' expected: '.$licence->token->id);
						//IP is difernet...
						return false;
					}
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function validateClient($client_id, $client_secret) {
		Log::info('LicenceService - validateClient START');
		
		if (isset($client_id) && isset($client_secret)) {
			
			$client = $this->clientRepository->find($client_id);
			
			Log::info($client);
			
			if ($client && $client->secret == $client_secret && !$client->revoked) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

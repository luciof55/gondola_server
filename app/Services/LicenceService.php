<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\LicenceRepository;
use Laravel\Passport\ClientRepository;
use Carbon\Carbon;
use Exception;

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
	
	public function all() {
		return $this->licenceRepository->paginateWithTrashed();
	}
	
	public function createLicence($client_id, $client_licence, $amount) {
		Log::info('LicenceService - createLicence START');
		
		if (isset($client_id) && isset($amount) && isset($client_licence)) {
			try {
				$licence = $this->licenceRepository->create(['client_id' => $client_id, 'licence' => $client_licence, 'licence_amount' => $amount]);
			
				if ($licence->save()) {
					Log::info('createLicence client licence created: '.$client_licence);
					return $licence;	
				} else {
					Log::error('createLicence -  Error while saving');
					return null;
				}
			} catch (Exception $e) {
				Log::error($e);
				return null;
			}
		} else {
			Log::info('createLicence -  missing parameters');
			return null;
		}
	}
	
	public function updateLicence($token, $ip, $hostname, $macAddress, $client_licence) {
		Log::info('LicenceService - updateLicence START');
		
		if (isset($token) && isset($ip) && isset($client_licence)) {
			try {
				$licence = $this->licenceRepository->findWhereFirst('licence', $client_licence);
				if ($licence) {
					$licence->ip = $ip;
					$licence->token_id = $token->id;
					$licence->hostid = $hostname;
					$licence->macAddress = $macAddress;
					$licence->save();
					Log::info('updateLicence client licence updated: '.$client_licence);
					return true;
				} else {
					Log::info('updateLicence client licence not found: '.$client_licence);
					return false;
				}
			} catch (Exception $e) {
				Log::error($e);
				return false;
			}
		} else {
			Log::info('updateLicence -  missing parameters');
			return false;
		}
	}
	
	public function deleteLicence($licenceId) {
		Log::info('LicenceService - deleteLicence START');
		
		if (isset($licenceId)) {
			try {
				$licence = $this->licenceRepository->find($licenceId);
				if ($licence) {
					if ($licence->token) {
						$this->releaseLicence($licence->token->id);
					}
					
					if ($this->licenceRepository->delete($licenceId)) {
						Log::info('deleteLicence client licence removed');
						return true;
					} else {
						Log::info('deleteLicence error while removing');
					return false;
					}
				} else {
					Log::info('deleteLicence client licence not found');
					return false;
				}
			} catch (Exception $e) {
				Log::error($e);
				return false;
			}
		} else {
			Log::info('deleteLicence -  missing parameters');
			return false;
		}
	}
	
	public function enableLicence($licenceId) {
		Log::info('LicenceService - enableLicence START');
		
		if (isset($licenceId)) {
			try {
				$licence = $this->licenceRepository->findWithTrashed($licenceId);
				if ($licence) {
					if ($licence->restore()) {
						Log::info('enableLicence client licence restored');
						return true;
					} else {
						Log::info('enableLicence error while restoring');
					return false;
					}
				} else {
					Log::info('enableLicence client licence not found');
					return false;
				}
			} catch (Exception $e) {
				Log::error($e);
				return false;
			}
		} else {
			Log::info('enableLicence -  missing parameters');
			return false;
		}
	}
	
	public function releaseLicence($tokenId) {
		Log::info('LicenceService - releaseLicence START');
		
		if (isset($tokenId)) {
			try {
				$licence = $this->licenceRepository->findWhereFirst('token_id', $tokenId);
				if ($licence) {
					$licence->ip = null;
					$licence->token_id = null;
					$licence->hostid = null;
					$licence->macAddress = null;
					$licence->remoteips = null;
					if ($licence->save()) {
						Log::info('releaseLicence client licence updated: '.$licence->licence);
						$token = $this->tokenRepository->find($tokenId);

						if (is_null($token)) {
							Log::info('releaseLicence token not found'. $tokenId);
							return false;
						}

						$token->revoke();
						return true;
					} else {
						Log::info('releaseLicence client licence updated: '.$licence->licence);
						return false;
					}
					
				} else {
					Log::info('releaseLicence client licence not found');
					return false;
				}
			} catch (Exception $e) {
				Log::error($e);
				return false;
			}
		} else {
			Log::info('releaseLicence -  missing parameters');
			return false;
		}
	}
	
	public function validateLicence($ip, $client_licence) {
		Log::info('LicenceService - validateLicence START');
		
		if (isset($ip) && isset($client_licence)) {
			try {
				$licence = $this->licenceRepository->findWhereFirst('licence', $client_licence);
			
				if ($licence) {
					if (!isset($licence->ip) && !isset($licence->token)) {
						return true;
					} else {
						Log::info('validateLicence client licence already used');
						return false;
					}
				} else {
					Log::info('validateLicence client licence not found');
					return false;
				}
			} catch (Exception $e) {
				Log::error($e);
				return false;
			}
			
		} else {
			Log::info('validateLicence -  missing parameters');
			return false;
		}
	}
	
	public function validateLicenceWithToken($ip, $remoteIp, $hostname, $macAddress, $client_licence, $token) {
		Log::info('LicenceService - validateLicenceWithToken START');
		
		if (isset($token) && isset($ip) && isset($client_licence)) {
			
			$licence = $this->licenceRepository->findWhereFirst('licence', $client_licence);
			
			if ($licence) {
				if (!isset($licence->ip) || !isset($licence->token)) {
					Log::info('validateLicenceWithToken no IP or Token activated');
					return false;
				} else {
					if ($ip == $licence->ip && $token->id == $licence->token->id) {
						if ($ip != $remoteIp) {
							//Validate remote IP and amount of licences
							return $this->validateAndUpdateLicenceAmount($licence, $remoteIp);
						} else {
							Log::info('validateLicenceWithToken recived IP : '.$ip.' match with remote IP');
							if ($macAddress == $licence->macaddress) {
								Log::info('validateLicenceWithToken Mac Address match: '. $macAddress);
								return true;
							} else {
								Log::info('validateLicenceWithToken recived Mac Address: '.$macAddress.' expected: '.$licence->macaddress);
								return false;
							}
						}
					} else {
						Log::info('validateLicenceWithToken recived IP: '.$ip.' expected: '.$licence->ip);
						Log::info('validateLicenceWithToken recived token: '.$token->id.' expected: '.$licence->token->id);
						//IP is differenet...
						if ($macAddress == $licence->macaddress) {
							try {
								Log::info('validateLicenceWithToken Mac Address match, updating licence IP: '. $ip);
								$licence->ip = $ip;
								$licence->save();
								return $this->validateLicenceWithToken($ip, $remoteIp, $hostname, $macAddress, $client_licence, $token);
							} catch (Exception $e) {
								Log::error($e);
								return false;
							}
						} else {
							Log::info('validateLicenceWithToken recived Mac Address: '.$macAddress.' expected: '.$licence->macaddress);
							return false;
						}
					}
				}
			} else {
				Log::info('validateLicenceWithToken client licence not found');
				return false;
			}
		} else {
			Log::info('validateLicenceWithToken -  missing parameters');
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
	
	protected function validateAndUpdateLicenceAmount($licence, $remoteIp) {
		//Validate remote IP and amount of licences
		if (is_null($licence->remoteips)) {
			$newRemoteIp = array('ip' => $remoteIp, 'time' => time());
			$remoteips = array();
			$replacedIp = str_replace_array('.', ['', '', '', ''], $remoteIp);
			$remoteips = array_add($remoteips, $replacedIp, $newRemoteIp);
			Log::info($remoteips);
			$licence->remoteips = json_encode($remoteips);
			try {
				$licence->save();
				Log::info('validateAndUpdateLicenceAmount Adding IP: '.$remoteIp);
				return true;
			} catch (Exception $e) {
				Log::error($e);
				return false;
			}
		} else {
			$replacedIp = str_replace_array('.', ['', '', '', ''], $remoteIp);
			$remoteips = json_decode($licence->remoteips, true);
			if (!array_has($remoteips, $replacedIp)) {
				$currentUsedLicences = count($remoteips);
				//Se resta la licencia de la instalación de la góndola
				if (($licence->licence_amount) > $currentUsedLicences) {
					$newRemoteIp = array('ip' => $remoteIp, 'time' => time());
					$remoteips = array_add($remoteips, $replacedIp, $newRemoteIp);
					$licence->remoteips = json_encode($remoteips);
					try {
						$licence->save();
						Log::info('validateAndUpdateLicenceAmount Adding IP: '.$remoteIp);
						return true;
					} catch (Exception $e) {
						Log::error($e);
						return false;
					}
				} else {
					Log::info('validateAndUpdateLicenceAmount not enough licences, current used: '.$currentUsedLicences);
					return false;
				}
			} else {
				Log::info('validateAndUpdateLicenceAmount IP already authorized: '.$remoteIp);
				return true;
			}
		}
	}
}

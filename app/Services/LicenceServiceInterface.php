<?php

namespace App\Services;

interface LicenceServiceInterface
{
	
	public function getToken($client_id, $client_secret, $client_user);
	
	public function all();
	
	public function createLicence($client_id, $client_licence, $amount);
	
	public function updateLicence($token, $ip, $hostname, $macAddress, $client_licence);
	
	public function deleteLicence($licenceId);
	
	public function releaseLicence($token);
	
	public function validateLicence($ip, $client_licence);
	
	public function validateLicenceWithToken($ip, $remoteIp, $hostname, $macAddress, $client_licence, $token);
	
	public function validateClient($client_id, $client_secret);
	
}

<?php

namespace App\Services;

interface LicenceServiceInterface
{
	
	public function getToken($client_id, $client_secret, $client_user);
	
	public function updateLicence($token, $ip, $client_license);
	
	public function validateLicence($ip, $client_licence);
	
	public function validateLicenceWithToken($ip, $client_licence, $token);
	
	public function validateClient($client_id, $client_secret);
	
}

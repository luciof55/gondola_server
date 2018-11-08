<?php

namespace App\Http\Controllers;

use Laravel\Passport\Http\Controllers\AccessTokenController;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ServerRequestInterface;

class EnrichTokenController extends AccessTokenController
{
	/**
     * Authorize a client to access the user's account.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @return \Illuminate\Http\Response
     */
    public function issueToken(ServerRequestInterface $request)
    {
		Log::info('YEAHoooooooooooooooooooooooooo');
		
		Log::info($request->getServerParams());
		
		Log::info('YEAH*********************************');
		
        $response = parent::issueToken($request);
		
		Log::info($response);
		
		return $response;
    }
}

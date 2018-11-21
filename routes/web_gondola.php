<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/clients', function () {
    return view('clients');
})->middleware('checkprivilege:clients')->name('clients');

Route::get('/tokens', function () {
    return view('tokens');
})->middleware('checkprivilege:licences')->name('licences');


Route::get('/getToken', 'TokenController@getToken')->middleware('auth:api')->name('getToken');

Route::get('/oauth/authorize', 'AuthorizeTokenController@authorize')->middleware('auth')->name('passport.authorizations.authorize');

Route::get('/oauth/tokens', 'AuthorizedClientsTokenController@forAllUser')->middleware('auth')->name('passport.tokens.index');

Route::post('/oauth/licence', 'AuthorizedClientsTokenController@addLicence')->middleware('auth')->name('licence.add');

Route::delete('/oauth/licence/{licence_id}', 'AuthorizedClientsTokenController@deleteLicence')->middleware('auth')->name('licence.delete');

Route::put('/oauth/licence/{licence_id}', 'AuthorizedClientsTokenController@enableLicence')->middleware('auth')->name('licence.enable');

Route::delete('/oauth/tokens/{token_id}', 'AuthorizedClientsTokenController@destroy')->middleware('auth')->name('passport.tokens.destroy');
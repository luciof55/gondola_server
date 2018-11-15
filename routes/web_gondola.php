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
Route::get('/tokens', function () {
    return view('tokens');
});

Route::get('/getToken', 'TokenController@getToken')->middleware('auth:api')->name('getToken');

Route::get('/oauth/authorize', 'AuthorizeTokenController@authorize')->middleware('auth')->name('passport.authorizations.authorize');
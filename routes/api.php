<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\AuthenticationController@login');
//Route::post('register', 'Api\AuthenticationController@register');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'Api\AuthenticationController@details');
    Route::post('register', 'Api\AuthenticationController@register');
    Route::get('generate/token', 'Api\AuthenticationController@generateApiToken');
});

Route::group(['middleware' => ['api_token'], 'prefix' => 'virus'], function () {
    Route::get('all', 'Api\VirusDataController@all');
    Route::get('allbycountry', 'Api\VirusDataController@allByCountry');
    Route::get('data', 'Api\VirusDataController@data');
});

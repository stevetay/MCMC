<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::get('/postfeed', [
    "as" => 'postfeed',
    "uses" => "adminController@postFeed"
]);

Route::group(array('before'=>'auth'), function()
{

	Route::group([ 'prefix'=>'api' ], function() {

	    Route::post('/postrest', [
	        "uses" => "ApiController@postRestaurant"
	    ]);    

	    Route::get('/getrest',[
	    	"uses" => "ApiController@getRestaurant"
    	]);

	});
});

Route::get('/dashbroad', [
	"as" => 'dashbroad',
	"uses" => "adminController@dashbroad"
]);


Route::get('/table', [
	"as" => 'table',
	"uses" => "adminController@table"
]);

Route::get('/ads/delete/{id}', [
	"as" => 'delete_ads',
	"uses" => "adminController@delete_ads"
]);

Route::get('/logout', [
	"as" => 'logout',
	"uses" => "adminController@logout"
]);



// Route::controllers([
// 	'auth' => 'Auth\AuthController',
// 	'password' => 'Auth\PasswordController',
// ]);

Route::get('fileentry', 'FileEntryController@index');
Route::get('fileentry/get/{filename}', [
	'as' => 'getentry', 'uses' => 'FileEntryController@get']);
Route::post('fileentry/add',[ 
        'as' => 'addentry', 'uses' => 'FileEntryController@add']);



Route::get('login', [
	'as' => 'login',
	'uses' => 'adminController@get'
]);

Route::group([ 'prefix'=>'api/v1.0' ], function() {

    Route::post('/login', [
        "uses" => "ApiController@loginAPI"
    ]);    

    Route::post('/ads/post',
     ['as' => 'postads', 'uses' => 'AdsController@post']
    );

    Route::get('/getads',[
    	"uses" => "ApiController@getAds"
	]);
});
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

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);



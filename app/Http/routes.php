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

// Route::get('/', 'WelcomeController@index');

// Route::get('home', 'HomeController@index');

// Route::get('/postfeed', [
//     "as" => 'postfeed',
//     "uses" => "adminController@postFeed"
// ]);

// Route::group(array('before'=>'auth'), function()
// {

// 	Route::group([ 'prefix'=>'api' ], function() {

// 	    Route::post('/postrest', [
// 	        "uses" => "ApiController@postRestaurant"
// 	    ]);    

// 	    Route::get('/getrest',[
// 	    	"uses" => "ApiController@getRestaurant"
//     	]);

// 	});
// });

// Route::get('/dashbroad', [
// 	"as" => 'dashbroad',
// 	"uses" => "adminController@dashbroad"
// ]);


// Route::get('/table', [
// 	"as" => 'table',
// 	"uses" => "adminController@table"
// ]);

// Route::get('/ads/delete/{id}', [
// 	"as" => 'delete_ads',
// 	"uses" => "adminController@delete_ads"
// ]);

// Route::get('/logout', [
// 	"as" => 'logout',
// 	"uses" => "adminController@logout"
// ]);



// // Route::controllers([
// // 	'auth' => 'Auth\AuthController',
// // 	'password' => 'Auth\PasswordController',
// // ]);

// Route::get('fileentry', 'FileEntryController@index');
// Route::get('fileentry/get/{filename}', [
// 	'as' => 'getentry', 'uses' => 'FileEntryController@get']);
// Route::post('fileentry/add',[ 
//         'as' => 'addentry', 'uses' => 'FileEntryController@add']);



Route::get('login', [
	'as' => 'login',
	'uses' => 'adminController@get'
]);

Route::group([ 'prefix'=>'api/v1.0' ], function() {

    Route::post('/login', [
        "uses" => "ApiController@loginAPI"
    ]);    

 //    Route::post('/ads/post',
 //     ['as' => 'postads', 'uses' => 'AdsController@post']
 //    );

 //    Route::post('/ads/editSched',
 //     ['as' => 'editSched', 'uses' => 'AdsController@editScheduled']
 //    );

 //    Route::post('/ads/editSolved',
 //     ['as' => 'editSolved', 'uses' => 'AdsController@editSolved']
 //    );

 //    Route::post('/uploadPhoto',
 //     ['as' => 'uploadPhoto', 'uses' => 'AdsController@uploadPhoto']
 //    );   

 //    Route::post('/uploadPhoto/solved',
 //     ['as' => 'uploadSolved', 'uses' => 'AdsController@uploadSolved']
 //    );     

 //    Route::get('/getads',[
 //    	"uses" => "ApiController@getAds"
	// ]);

 //    Route::get('/getFilterComplain',[
 //    	"uses" => "ApiController@getFilterComplain"
	// ]);

	// Route::post('/postFollower',[
	// 	'as' => 'postFollower', 'uses' => 'AdsController@postFollower'
	// ]);

 //    Route::get('/getOwnFollower',[
 //    	"uses" => "ApiController@getOwnFollower"
	// ]);
});

Route::post('/register', [
    "uses" => "ApiController@register"
]);

Route::post('/login', [
    "uses" => "ApiController@login"
]);

Route::post('/logout', [
    "uses" => "ApiController@logout"
]);

Route::post('/checksession', [
    "uses" => "ApiController@checksession"
]);
<?php namespace App\Http\Controllers;

use DB;
use App\Quotation;

class adminController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function postFeed()
	{

		DB::table('restaurantList')->insert(
		    //array('name' => 'Restaurant 1', 'thumbnail' => "https://dl.dropboxusercontent.com/u/93617869/restaurant/restaurant1.jpg"),
		    //array('name' => 'Restaurant 2', 'thumbnail' => "https://dl.dropboxusercontent.com/u/93617869/restaurant/restaurant2.jpg")
		    array('name' => 'Restaurant 3', 'thumbnail' => "https://dl.dropboxusercontent.com/u/93617869/restaurant/restaurant3.jpg")
		);
	}

}
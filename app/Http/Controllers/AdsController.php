<?php namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Advertisement;
use App\Complain;
use App\uploadPhoto;

use Request;
use Exception;
use DB;
 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

use App\Seeties\Ads\Auth;

 
class AdsController extends RegionController {
 
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
	// public function index()
	// {
	// 	$entries = Fileentry::all();
 
	// 	return view('fileentries.index', compact('entries'));
	// }
	public function getFeed() {
	
		// /dd(\Cookie::get('userID'));
		$results = DB::collection('advert')->get();

		for ($i = 0, $c = count($results); $i < $c; ++$i) {

			$results[$i]['advID'] = $results[$i]['_id']->{'$id'};
			unset($results[$i]['_id']);
			unset($results[$i]['created_at']);
			unset($results[$i]['updated_at']);
		    $results[$i] = (array) $results[$i];

		} 

		return \Response::json($results,200);

	}
 
	// public function post() { 

	// 	// $errors = array_filter(Request::all());
	// 	// var_dump($errors);
	// 	// exit;

	// 	//if (Request::has('complained_by') && Request::has('address') && Request::has('title') && Request::hasFile('description')  ) {
	// 	if (Request::has('complained_by') && Request::has('address') && Request::has('title') ) {

	// 		// try {

	// 		// 	$file = Request::file('thumbnail');
	// 		// 	$extension = $file->getClientOriginalExtension();
	// 		// 	$destinationPath = 'upload/';
	// 		// 	$filename = $file->getFilename().'.'.$extension;
	// 		// 	$moved = \Input::file('thumbnail')->move($destinationPath, $filename);
	// 		// 	$file->getClientMimeType();

	// 		// } catch (\Exception $e) {

	// 		//     return \Response::json($e->getMessage(), 400);

	// 		// }

	// 		$entry = new Complain();
	// 		//$idIncrement = $entry->increment('id');

	// 		// try
	// 		// {
	// 		$increment = Complain::orderBy('complain_id', 'desc')->get()->first()->complain_id + 1;
	// 		// }
	// 		// catch(PDOException $exception)
	// 		// {
	// 		//$increment =  1;
	// 		// }
	// 		//dd(Complain::orderBy('created_at', 'desc')->get()->first()->id);
			
	// 		$entry->complain_id = $increment;
	// 		$entry->complained_by = Request::input('complained_by');
	// 		$entry->longitude = Request::input('longitude');
	// 		$entry->latitude = Request::input('latitude');
	// 		$entry->address = Request::input('address');
	// 		$entry->title = Request::input('title');
	// 		$entry->describe = Request::input('describe');
	// 		$entry->category = Request::input('category');
	// 		//$entry->thumbnail = "upload/".$filename;
	// 		$entry->created_on = Request::input('created_on');
	// 		$entry->status = "assigned";
			
	// 		$entry->save();

	// 		$return = \Response::json($entry, 200);

	// 		return $return;

	// 	} else {

	// 		$return = \Response::json([
	// 	         "ErrorCode"=>"4",
	// 	         "ErrorCodeDescription"=> "*Required All",
	// 	         "Error field" => Request::all()
	// 		], 400);

	// 		return $return;

	// 	}
	// }

	// public function uploadPhoto() {

	// 	if ($_GET['id'] && Request::file('thumbnail')) {
			
	// 		try {

	// 			$complain = Complain::where('complain_id',  intval($_GET['id'] ) )->get()->first();

	// 			$file = Request::file('thumbnail');
	// 			$extension = $file->getClientOriginalExtension();
	// 			$destinationPath = 'upload/';
	// 			$filename = $file->getFilename().'.'.$extension;
	// 			$moved = \Input::file('thumbnail')->move($destinationPath, $filename);
	// 			$file->getClientMimeType();

	// 			//$upload->complain_id = $_GET['id'] ;
	// 			$complain->thumbnail = "upload/".$filename;
	// 			$complain->save();

	// 			$return = \Response::json($complain, 200);

	// 			return $return;

	// 		} catch (\Exception $e) {

	// 		    return \Response::json($e->getMessage(), 400);

	// 		}

	// 	} else {

	// 		$return = \Response::json([
	// 	         "ErrorCode"=>"4",
	// 	         "ErrorCodeDescription"=> "Invalid Id"
	// 		], 400);

	// 		return $return;

	// 	}

	// }

	// public function editScheduled() {

	// 	if (Request::has('complain_id')) {

	// 		$complain = Complain::where('complain_id',  intval(Request::input('complain_id')) )->get()->first();
	// 		// /dd($complain);
	// 		$complain->scheduled_date = Request::input('scheduled_date');
	// 		$complain->duration = Request::input('duration');
	// 		$complain->status = 'scheduled';
	// 		$complain->save();

	// 		$complain['id'] = intval(Request::input('complain_id'));

	// 		$return = \Response::json($complain, 200);

	// 		return $return;

	// 	} else {

	// 		$return = \Response::json([
	// 	         "ErrorCode"=>"4",
	// 	         "ErrorCodeDescription"=> "*Required All",
	// 	         "Error field" => Request::all()
	// 		], 400);

	// 		return $return;

	// 	}
	// }

	// public function editSolved() {

	// 	if (Request::has('complain_id')) {

	// 		$complain = Complain::where('complain_id',  intval(Request::input('complain_id')) )->get()->first();
	// 		// /dd($complain);
	// 		$complain->resolution_date = Request::input('resolution_date');
	// 		$complain->resolution_describe = Request::input('resolution_describe');
			
	// 		$complain->status = 'solved';
	// 		$complain->save();
	// 		$return = \Response::json($complain, 200);

	// 		return $return;

	// 	} else {

	// 		$return = \Response::json([
	// 	         "ErrorCode"=>"4",
	// 	         "ErrorCodeDescription"=> "*Required All",
	// 	         "Error field" => Request::all()
	// 		], 400);

	// 		return $return;

	// 	}
	// }

	// public function uploadSolved() {

	// 	if ($_GET['id'] && Request::file('thumbnail')) {
			
	// 		try {

	// 			$complain = Complain::where('complain_id',  intval($_GET['id'] ) )->get()->first();

	// 			$file = Request::file('thumbnail');
	// 			$extension = $file->getClientOriginalExtension();
	// 			$destinationPath = 'upload/';
	// 			$filename = $file->getFilename().'.'.$extension;
	// 			$moved = \Input::file('thumbnail')->move($destinationPath, $filename);
	// 			$file->getClientMimeType();

	// 			//$upload->complain_id = $_GET['id'] ;
	// 			$complain->resolution_image = "upload/".$filename;
	// 			$complain->save();

	// 			$return = \Response::json($complain, 200);

	// 			return $return;

	// 		} catch (\Exception $e) {

	// 		    return \Response::json($e->getMessage(), 400);

	// 		}

	// 	} else {

	// 		$return = \Response::json([
	// 	         "ErrorCode"=>"4",
	// 	         "ErrorCodeDescription"=> "Invalid Id"
	// 		], 400);

	// 		return $return;

	// 	}

	// }


	// public function postFollower() {

	// 	if (Request::has('complain_id') && Request::has('user_id') ) {

	// 		//$complain = Complain::where('complain_id',  intval( Request::input('complain_id') ) );
	// 		// DB::collection('complains')->where('complain_id',  intval( Request::input('complain_id') ) )->push('follower', 
	// 		// 	array( "id" => Request::input('id') ) );

	// 		DB::collection('complains')->where('complain_id',  intval( Request::input('complain_id') ) )->push('follower', array('id' =>  intval( Request::input('user_id') ) ));


	// 		//$complain->follower = array( "id" => Request::input('id') );
	// 		//$complain->push('follower', array( "id" => Request::input('id') ) );

	// 		// $complain->update();

	// 		$return = \Response::json([
	// 			"result" => true
	// 		], 200);
			

	// 		return $return;

	// 	} else {

	// 		$return = \Response::json([
	// 	         "ErrorCode"=>"4",
	// 	         "ErrorCodeDescription"=> "*Required All",
	// 	         "Error field" => Request::all()
	// 		], 400);

	// 		return $return;

	// 	}
	// }

}
 
 
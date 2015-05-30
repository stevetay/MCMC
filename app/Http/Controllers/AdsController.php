<?php namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Advertisement;
use Request;
 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
 
class AdsController extends Controller {
 
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
 
	public function post() {

		// $errors = array_filter(Request::all());
		// var_dump($errors);
		// exit;

		if (Request::has('title') && Request::has('desc') && Request::has('dateFrom') && Request::hasFile('filefield')  ) {


			$file = Request::file('filefield');
			$extension = $file->getClientOriginalExtension();

			$destinationPath = 'upload/';
			$filename = $file->getFilename().'.'.$extension. '.' . \Input::file('filefield')->getClientOriginalExtension();
			$moved = \Input::file('filefield')->move($destinationPath, $filename);

			//Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
			
			$entry = new Advertisement();
			$entry->adv_mime = $file->getClientMimeType();
			$entry->adv_thumbnail = "upload/".$filename;
			$entry->adv_title = Request::input('title');
			$entry->adv_desc = Request::input('desc');
			$entry->adv_expdate = strtotime(Request::input('dateFrom'));

			$entry->save();

			$return = \Response::json([
			     "status" => "ok",
			     "entry" => $entry
			], 200);
			return $return;


		} else {


			$return = \Response::json([
			     "error" => true,
			     "message" => "*Required All",
			     "required field" => Request::all()
			], 400);
			return $return;

		}
	}
}
 
 
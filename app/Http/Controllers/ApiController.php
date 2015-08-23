<?php
namespace App\Http\Controllers;
use Input;
use Request;
use DB;
use App\Quotation;
use App\Complain;

class ApiController extends Controller {

    public function postRestaurant() {

        if(Input::get('token')=="123qwe" && Input::hasFile('image')) {

            if (Input::hasFile('image'))
            {

                $return = \Response::json([
                    "error" => "0",
                    "got_token" => "invalid image"
                ], 200);

            } elseif (Input::get('name')) {

                $return = \Response::json([
                    "error" => "0",
                    "got_token" => Input::get('token')."-".Input::file('image')
                ], 200);

            } else {

                $return = \Response::json([
                    "status" => "ok",
                    "got_token" => Input::get('token')."-".Input::get('image')
                ], 200);

            }

            return $return;

        } else {

            $return = \Response::json([
                "error" => "0",
                "got_token" => Input::get('token')
            ], 200);
            return $return;
        }
    }

    public function getRestaurant() {

        // Make sure current user owns the requested resource
        $results = DB::select('select * from restaurantList');

        for ($i = 0, $c = count($results); $i < $c; ++$i) {
            $results[$i] = (array) $results[$i];
        }

        //return $results->toArray();
        return \Response::json(array(
            'error' => false,
            'restaurantList' => $results),
            200
        );
    }

    public function loginAPI() {

        if(Input::get('name')=="admin"&&sha1(Input::get('password'))==="05fe7461c607c33229772d402505601016a7d0ea") {

            $return = \Response::json([
                "status" =>  "ok",
                "message" => "Sucessfull Login!"
            ], 200);
            \Session::put('tempuser.token', sha1(Input::get('password')) );

            return $return;

        } else {

            return \Response::json([
                'error' => true,
                'message' => "Error, Invalid name or password",
            ], 400);

        }

    }

    public function getAds() {

        // Make sure current user owns the requested resource
        $results = DB::collection('complains')->orderBy('created_on', 'desc')->get();

        for ($i = 0, $c = count($results); $i < $c; ++$i) {
            $results[$i] = (array) $results[$i];
        }

        //return $results->toArray();
        return \Response::json($results,200);

    }

    public function getFilterComplain() {

        if(@$_GET['public_id'] || @$_GET['me']) {
            // Make sure current user owns the requested resource
            // 
            if(@$_GET['public_id']) {

                $results = DB::collection('complains')->where('complained_by', '!=', $_GET['public_id'] )->orderBy('created_on', 'desc')->get();

            } else if(@$_GET['me']) {

                $results = DB::collection('complains')->where('complained_by', intval($_GET['me']) )->orderBy('created_on', 'desc')->get();

            } else {

                $results = DB::collection('complains');

            }
            
            //dd($results);
            for ($i = 0, $c = count($results); $i < $c; ++$i) {
                $results[$i] = (array) $results[$i];
            }

            //return $results->toArray();
            return \Response::json($results,200);

        } else {

            $return = \Response::json([
                 "ErrorCode"=>"4",
                 "ErrorCodeDescription"=> "Please input Id"
            ], 400);

            return $return;
        }


    }

    public function getOwnFollower() {

        // Make sure current user owns the requested resource
        // 
        if(@$_GET['id']) {
        
            $results = DB::collection('complains')->where('follower', array('id'  =>  intval( Request::input('id') ) ) )->get();
            //$results = Complain::where('follower', array('id'  =>  intval( Request::input('id') ) ) )->get();

            for ($i = 0, $c = count($results); $i < $c; ++$i) {
                $results[$i] = (array) $results[$i];
            }

            //return $results->toArray();
            return \Response::json($results,200);

        } else {

            $return = \Response::json([
                 "ErrorCode"=>"4",
                 "ErrorCodeDescription"=> "Please input Id"
            ], 400);

            return $return;
        }

    }


}
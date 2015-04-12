<?php
namespace App\Http\Controllers;
use Input;
use Request;
use DB;
use App\Quotation;

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

}
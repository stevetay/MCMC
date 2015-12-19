<?php

namespace App\Http\Controllers;
use App\Seeties\Exceptions\AuthCheckException;

use App\Seeties\Users\Auth;

class RegionController extends Controller
{
    const COOKIE_EXPIRE = 43200;

    public function __construct()
    {

 		$this->autoLogin();
        
    }

    private function autoLogin()
    {
        try {

            if (\Session::has('userID')) {

                
            } else {

                //try set session from cookies if no session
                if (!empty(\Cookie::get('userID'))) {

                    $field = array(
                        'field' => '_id',
                        'value' => (string) \Cookie::get('userID'),
                    );

                    if (Auth::isExists($field)) {

                        \Session::put('userID', \Cookie::get('userID')  );

                        //
                        //return \Response::make()->withCookie(\Cookie::make('userID', \Cookie::get('userID') , self::COOKIE_EXPIRE));
                       
                    } else {

                        throw new AuthCheckException('username', 'auth.username.doesnt.exist');

                    }

                } else {

                    //\Session::forget('userID')->withCookie(\Cookie::forget('userID'))->withCookie(\Cookie::forget('userID'));

                    throw new AuthCheckException('userid', 'auth.userid.doesnt.exist');
                }
            }
            
        } catch (Exception $e) {


            $return = \Response::json([
                 "message"=> "Session logout!",
            ], 400 );

            \Session::forget('userID');
            return $return->withCookie(Cookie::forget('userID'))->withCookie(Cookie::forget('userID'));

        }

    }

}

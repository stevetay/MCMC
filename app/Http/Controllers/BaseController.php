<?php

namespace App\Http\Controllers;
use App\Seeties\Exceptions\AuthCheckException;

class BaseController extends Controller
{

    public function __construct()
    {

        if (\Session::has('tempuser.token')) {
                
        } else {
            //dd(\Session::has('tempuser.token'));
            throw new AuthCheckException('base', 'invalid session');
        }
        
    }

}

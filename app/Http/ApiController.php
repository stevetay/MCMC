<?php

class ApiController extends BaseController {

    public function postTempLogin() {

        if(Input::get('token')) {

            $return = \Response::json([
                "status" => "temp ok",
                "got_token" => Input::get('token')
            ], 200);

            return $return;

        } else {

            return $this->errorjson("Error cannot get token id.", 0, 200);
        }
    }

}
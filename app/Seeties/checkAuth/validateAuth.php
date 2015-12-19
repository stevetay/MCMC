<?php

namespace App\Seeties\checkAuth;
use DB;
use App\Models\Users\UsersModel;
use App\Seeties\Utils\Utils;
use App\Seeties\Exceptions\AuthCheckException;

class validateAuth
{
    const RESET_CODE_LIFETIME = 86400; // 24 hours
    const USER_ROLE_EXPERT = 'expert';
    const USER_ROLE_USER = 'user';
    const LOGIN_TYPE_FB = 'fb';
    const LOGIN_TYPE_INSTA = 'insta';
    const LOGIN_TYPE_EMAIL = 'email';

    private static $_me;

    public static function validateAppKey($appkey){

        if(sha1($appkey)=="05fe7461c607c33229772d402505601016a7d0ea"){
            return true;
        } else {
            throw new AuthCheckException('appkey', 'auth.appkey.invalid');
        }
    }

    public static function validateUsername($username)
    {
        
        $username = (string) trim(strtolower($username));

        if (preg_match('/[^A-Za-z0-9\\-\\_]+/', $username)) {
            throw new AuthCheckException('username', 'auth.username.alpha_dash');
        }
        $data = array(
            'username' => $username,
        );

        $field = array(
            'field' => 'username',
            'value' => (string) $username,
        );

        // TODO: check the username in agency collection
        if (self::isExists($field)) {
            //|| \Seeties\Agency\Agency::isExists($field, null)) {
            throw new AuthCheckException('username', 'auth.username.exist');
        }

        return true;
    }

    public static function validateEmail($email , $type)
    {
      
        $email_name = substr($email, 0, strpos($email, '@'));

        if (preg_match('/[^A-Za-z0-9\\-\\_\\.]+/', $email_name)) {
            throw new AuthCheckException('email', 'auth.email.email');
        }

        if(filter_var($email,FILTER_VALIDATE_EMAIL) === false)
        {
           throw new AuthCheckException('email', 'email is not valid');
        }

        if($type!="login") {
            $query = array(
                    'email' => new \MongoRegex('/'.$email.'$/i'),
            );

            $user = UsersModel::where($query)->first();

            if ($user) {
                throw new AuthCheckException('email', 'auth.email.exist');
            }
        }

        return true;
    }


    public static function validatePassword($password)
    {

        if (strlen($password) < 8 ) {
            throw new AuthCheckException('password', 'auth.password.lenght');
        }

        return true;
    }

    public static function validateUpload($input) {

        $all_uploads = $input;

        // Make sure it really is an array
        if (!is_array($all_uploads)) {
            $all_uploads = array($all_uploads);
        }

        $error_messages = array();

        // Loop through all uploaded files
        foreach ($all_uploads as $upload) {


            $validator = \Validator::make(
                array('file' => $upload),
                array('file' => 'required|mimes:jpeg,png|image|max:1000')
            );

            if ($validator->passes()) {
                // Do something
                return true;

            } else {
                //dd($validator->messages()->first('file'));
                // Collect error messages
                throw new AuthCheckException('validatFile', $validator->messages()->first('file') );
                //$error_messages[] = 'File "' . $upload->getClientOriginalName() . '":' . $validator->messages()->first('file');
            }
        }

        // Redirect, return JSON, whatever...
      
        throw new AuthCheckException('validatFile', $error_messages );

    }



    public static function validateAll($input , $inputValid){
       
       $validator = \Validator::make($input, $inputValid);

        if ($validator->fails()){
            //dd($validator->messages());
            throw new AuthCheckException('validatFile', $validator->messages()->first() );

        } else {
            return true;
        }

    }

    public static function validateNumeric($input){
       // dd($input);
       $validator = \Validator::make(
            array('advTitle' => $input),
            array('advTitle' => 'numeric')
        );

        if ($validator->fails()){
            
            throw new AuthCheckException('validatFile', $validator->messages()->first() );

        } else {
            return true;
        }

    }


    public static function validateType($str , $arr) {

        if(in_array($str , $arr)) {

            return true;

        } else {

            throw new AuthCheckException('validatType', 'invalid type' );
        }

    }

    public static function validateVideo($input) {

        $all_uploads = $input;

        // Make sure it really is an array
        if (!is_array($all_uploads)) {
            $all_uploads = array($all_uploads);
        }

        $error_messages = array();

        // Loop through all uploaded files
        foreach ($all_uploads as $upload) {


            $validator = \Validator::make(
                array('file' => $upload),
                array('file' => 'required|mimes:flv,mp4,m3u8,ts,3gp,mov,avi,wmv')
            );

            if ($validator->passes()) {
                // Do something
                return true;

            } else {
                //dd($validator->messages()->first('file'));
                // Collect error messages
                throw new AuthCheckException('validatFile', $validator->messages()->first('file') );
                //$error_messages[] = 'File "' . $upload->getClientOriginalName() . '":' . $validator->messages()->first('file');
            }
        }

        // Redirect, return JSON, whatever...
      
        throw new AuthCheckException('validatFile', $error_messages );

    }
  
}

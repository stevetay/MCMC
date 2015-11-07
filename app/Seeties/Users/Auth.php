<?php

namespace App\Seeties\Users;
use DB;
use App\Models\Users\UsersModel;
use App\Seeties\Utils\Utils;
use App\Seeties\Exceptions\AuthCheckException;

class Auth
{
    const RESET_CODE_LIFETIME = 86400; // 24 hours
    const USER_ROLE_EXPERT = 'expert';
    const USER_ROLE_USER = 'user';
    const LOGIN_TYPE_FB = 'fb';
    const LOGIN_TYPE_INSTA = 'insta';
    const LOGIN_TYPE_EMAIL = 'email';

    private static $_me;

    public static function user()
    {
        return self::$_me;
    }


    private static function create(array $data)
    {

        if (self::validateRegister($data)) {
            $user = new UsersModel();

            $user->username = $data['userName'];
            $user->password = sha1($data['userPassword']);
            $user->email = $data['userEmail'];
            $user->picture = $data['userPicture'];
            $user->save();

            \Session::put('userID', $user['_id']  );

            return $user;
        }

    }

    private static function login(array $data)
    {   
        if (self::validateLogin($data)) {
            $user = new UsersModel();

            $result = DB::collection('user')->where('email', $data['userEmail'] )->get();
            
            if($result){
                if($result[0]['password']==sha1($data['userPassword'])) {

                    \Session::put('userID', $result[0]['_id']->{'$id'} );

                    return $result[0];

                } else {

                    throw new AuthCheckException('password', 'auth.password.incorrect');
                }   
            } else {

                throw new AuthCheckException('username', 'auth.no.username');

            }
        }
    }

    public static function createUser(array $data)
    {

        // create user with form
        $required_fields = array(
            'userEmail',
            'userPassword',
            'userName',
            'userPicture',
            'appKey'
        );

        Utils::arrayKeyRequired($required_fields, $data);

        $new_user_info = self::create($data);

        return $new_user_info;
    }

    public static function loginUser(array $data)
    {

        // create user with form
        $required_fields = array(
            'userEmail',
            'userPassword',
            'appKey'
        );

        Utils::arrayKeyRequired($required_fields, $data);

        $new_user_info = self::login($data);

        return $new_user_info;
    }

    public static function validateLogin(array $data)
    {
 
        $data['userEmail'] = (string) trim($data['userEmail']);
        //$data['userName'] = (string) trim(strtolower($data['userName']));
        
        self::validateAppKey($data['appKey']);
        self::validateEmail($data['userEmail'] , "login");
        self::validatePassword($data['userPassword']);

        return true;
    }

    public static function validateRegister(array $data)
    {
 
        $data['userEmail'] = (string) trim($data['userEmail']);
        $data['userName'] = (string) trim(strtolower($data['userName']));
        
        self::validateAppKey($data['appKey']);
        self::validateUsername($data['userName']);
        self::validateEmail($data['userEmail']);
        self::validatePassword($data['userPassword']);

        return true;
    }

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


    public static function isExists(array $data)
    {
        $required_fields = array(
            'field',
            'value',
        );

        $field = $data['field'];
        $value = $data['value'];


        $query = array(
            $field => $value,
        );


        $user_info = UsersModel::where($query)->first();

        $result = (empty($user_info)) ? false : true;

        return $result;
    }
  
}

<?php 

namespace App\Seeties\Ads;
use DB;
use App\Models\Ads\AdsModel;
use App\Seeties\Utils\Utils;
use App\Seeties\checkAuth\validateAuth;

class Auth
{

	private static function create(array $data) {

		if (self::validateAds($data)) {

            //dd($data);
            $advImages = $data['advImages'];

            $inputAdvImage = array();

            foreach($advImages as $advImage){

                $file = $advImage;
                $extension = $file->getClientOriginalExtension();
                $destinationPath = 'upload/';
                $filename = $file->getFilename().'.'.$extension;
                $moved = $file->move($destinationPath, $filename);
                $file->getClientMimeType();

                $inputAdvImage[] = "upload/".$filename;

            }

            $advThumbnail = $data['advThumbnail'];
            $extensionThumbnail = $advThumbnail->getClientOriginalExtension();
            $destinationPathThumbnail = 'upload/';
            $filenameThumbnail = $advThumbnail->getFilename().'.'.$extensionThumbnail;
            $movedThumbnail = $advThumbnail->move($destinationPathThumbnail, $filenameThumbnail);
            $advThumbnail->getClientMimeType();

            $inputAdvThumbnail = "upload/".$filenameThumbnail;

		    $ads = new AdsModel();

            $ads->advTitle = $data['advTitle'];
            $ads->advContent = $data['advContent'];
            $ads->advType = $data['advType'];
            $ads->advThumbnail = $inputAdvThumbnail;
            $ads->advImages = $inputAdvImage;
            $ads->advLong = $data['advLong'];
		    $ads->advLat = $data['advLat'];

            if(isset($data['advUrl'])) {
                $ads->advUrl = $data['advUrl'];
            }            

            if(isset($data['advVideo'])) {

                $advVideo = $data['advVideo'];
                $extensionVideo = $advVideo->getClientOriginalExtension();
                $destinationPathVideo = 'upload/';
                $filenameVideo = $advVideo->getFilename().'.'.$extensionVideo;
                $movedVideo = $advVideo->move($destinationPathVideo, $filenameVideo);
                $advVideo->getClientMimeType();

                $inputAdvVideo = "upload/".$filenameVideo;
                $ads->advVideo = $inputAdvVideo;
            }

		    // $user->password = sha1($data['userPassword']);
		    // $user->email = $data['userEmail'];
		    // $user->picture = $data['userPicture'];
		    $ads->save();

		    return $ads;
		}

	}

    public static function createAds($data)
    {
        //dd($data);
    	// create user with form
    	$required_fields = array(
    	    'advTitle',
    	    'advContent',
    	    'advType',
    	    'advThumbnail',
    	    'advImages',
    	    'advLong',
    	    'advLat',
    	    'appKey'
    	);

    	Utils::arrayKeyRequired($required_fields, $data);

    	$new_ads = self::create($data);

    	return $new_ads;

    }

    public static function validateAds(array $data) {
    
    	validateAuth::validateAppKey($data['appKey']);
    	validateAuth::validateUpload($data['advThumbnail']);
        validateAuth::validateUpload($data['advImages']);

        if(self::is_exist_key("advUrl", $data) ) {
            // /dd("iskey");
            validateAuth::validateAll(           
                self::advUrl_array($data),
                self::required_advUrl_array()
            );
        } 

        if(self::is_exist_key("advVideo", $data) ) {

            validateAuth::validateAll(           
                self::advVideo_array($data),
                self::required_advVideo_array()
            );

            validateAuth::validateVideo($data['advVideo']);

        }

        validateAuth::validateAll(           
            self::adv_array($data),
            self::required_adv_array()
        );

        $type = array("image", "video", "text");

        validateAuth::validateType( $data['advType'], $type );

    	return true;

    }

    public static function is_exist_key($key, $search_array) {

        if (array_key_exists( $key , $search_array)) {

            return true;

        }

    }

    public static function advUrl_array($data) {

        $array = array(
                    'advUrl' => $data['advUrl'],
                );
        return $array;
    }


    public static function required_advUrl_array() {

        $array = array(
                    'advUrl' => 'required|active_url'
                );
        return $array;
    }

    public static function advVideo_array($data) {

        $array = array(
                    'advVideo' => $data['advVideo'],
                );
        return $array;
    }


    public static function required_advVideo_array() {

        $array = array(
                    'advVideo' => 'required'
                );
        return $array;
    }

    public static function adv_array($data) {

        $array = array(
                    'advTitle' => $data['advTitle'],
                    'advContent' => $data['advContent'],
                    'advType' => $data['advType'],
                    'advLong' => $data['advLong'],
                    'advLat' => $data['advLat'],
                );
        return $array;
    }

    public static function required_adv_array() {

        $array = array(
            'advTitle' => 'required',
            'advContent' => 'required',
            'advType' => 'required',
            'advLong' => 'required|numeric',
            'advLat' => 'required|numeric'
        );
        return $array;

    }

}
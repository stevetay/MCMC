<?php

namespace App\Seeties\Utils;

use App\Models\Posts\PostsModel;
use App\Seeties\Exceptions\ArrayKeyRequiredException;
use App\Seeties\Users\Auth;
use GeoIp2\Database\Reader;

final class Utils
{
    /**
     * estimated earth radians ~= 6378160 meters
     * formular: 360 / (6378160 * 2 * PI).
     */
    const DEGREE_PER_METER = 0.00000898312045;

    /**
     * estimated earth circumference.
     * formular: 6378160 * 2 * PI.
     */
    const MAX_DISTANCE_IN_METER = 40075161.12;

    public static function getClientIpAddress()
    {
        $header = \Request::header();

        if (array_key_exists('X-Forwarded-For', $header) && !empty($header['X-Forwarded-For'])) {
            return $header['X-Forwarded-For'];
        }

        if (\Config::get('app.debug')) {
            return '121.122.2.245';
        }

        return \Request::getClientIp();
    }

    public static function getClientLocation($ip_address)
    {
        $location = '';

        if (empty($ip_address)) {
            return $location;
        }

        $geo = new Reader(base_path().'/database/GeoLite2-City.mmdb');
        $record = $geo->city($ip_address);

        if (!empty($record)) {
            $location = array(
                'ip' => $ip_address,
                'country_code' => $record->country->isoCode,
                'country' => $record->country->name,
                'city' => $record->city->name,
                'postal' => $record->postal->code,
                'latitude' => $record->location->latitude,
                'longitude' => $record->location->longitude,
            );
        }
        /*
        $url = 'http://api.stylar.com/geoip/index.php?ip='.$ip_address;
        $crawler = \Seeties\Utils::webCrawler($url);
        $response = \Seeties\Utils::jsonDecodeToArray($crawler['content']);

        if ($crawler['http_code'] != 200 || empty($response)) {
            return $location;
        }

        $location = $response;
        */
        return $location;
    }

    public static function reverseGeo($data, $reverse_full = false)
    {
        $languages = \Config::get('seeties.reverse_geo.languages');

        $return = array();

        foreach ($languages as $lang => $lang_id) {
            $reverse_geo_lang = 'reverse_geo.'.$lang_id;
            // $language = '&language='.$lang;
            $data['language'] = $lang;
            $reverse = self::reverseGeoCall($data);
            $return[$lang_id] = $reverse;
        }

        return $return;
    }

    public static function reverseGeoCall($data)
    { // pass only long and latitude
        $language = '&language=';
        if (!empty($data['lng']) && !empty($data['lat'])) {
            if (!empty($data['language'])) {
                $language = $language.$data['language'];
            }
            $url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&latlng='.$data['lat'].','.$data['lng'].$language;
            //$url = "http://stylar.com/reverse_geo.php?latlng=".$data['lat'].",".$data['lng'].$language;
            // $url = "http://mail.stylar.com/reverse_geo.php?latlng=".$data['lat'].",".$data['lng'].$language;

            $options = array(
                CURLOPT_RETURNTRANSFER => true, // return web page
                CURLOPT_HEADER => false, // don't return headers
                CURLOPT_FOLLOWLOCATION => true, // follow redirects
                CURLOPT_ENCODING => '', // handle all encodings
                CURLOPT_AUTOREFERER => true, // set referer on redirect
                CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
                CURLOPT_TIMEOUT => 120, // timeout on response
                CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
                CURLOPT_SSL_VERIFYPEER => true, // enabled SSL Cert checks
                CURLOPT_POSTFIELDS => null,
                CURLOPT_POST => false,
                CURLOPT_HTTPGET => true,
            );
            $ch = curl_init($url);
            curl_setopt_array($ch, $options);
            $content = curl_exec($ch);
            curl_close($ch);
            $reverse = array();
            $maps_data = json_decode($content, true);

            if ($maps_data['status'] == 'OK') { // di};
                if (!empty($maps_data['results'])) {
                    if (!empty($maps_data['results'][0])) {
                        $v = $maps_data['results'][0];
                        foreach ($v['address_components'] as $y) {
                            foreach ($y['types'] as $type) {
                                $y['long_name'] = self::addressValueCorrector($y['long_name']);
                                $reverse['address_components'][$type] = $y['long_name'];
                            }
                        }
                        if (!empty($v['formatted_address'])) {
                            $reverse['formatted_address'] = $v['formatted_address'];
                        }
                    }

                    return $reverse;
                }
            }

            return;
        }
    }

    public static function addressValueCorrector($value)
    {
        $correcting = array(
            'ฟลิปปนส์' => 'ฟิลิปปินส์',
        );
        if (!empty($correcting[$value])) {
            $value = $correcting[$value];
        }

        return $value;
    }

    public static function webCrawler($url, array $options = array())
    {
        $options = $options + array(
            CURLOPT_RETURNTRANSFER => true, // return web page
            CURLOPT_HEADER => false, // don't return headers
            CURLOPT_FOLLOWLOCATION => true, // follow redirects
            CURLOPT_ENCODING => '', // handle all encodings
            CURLOPT_USERAGENT => 'spider', // who am i
            CURLOPT_AUTOREFERER => true, // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
            CURLOPT_TIMEOUT => 120, // timeout on response
            CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
            CURLOPT_SSL_VERIFYPEER => true,
        ); // enabled SSL Cert checks

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        $header['errno'] = $err;
        $header['errmsg'] = $errmsg;
        $header['content'] = $content;

        return $header;
    }

    public static function jsonDecodeToArray($data)
    {
        if (!is_string($data)) {
            throw new \Exception('parameter 1 should be string, '.gettype($data).' given.');
        }

        $json = json_decode($data);

        return self::jsonToArray($json);
    }

    private static function jsonToArray($data)
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = array_map(__METHOD__, array(
                    $v,
                ))[0];
            }
        }

        return $data;
    }

    public static function convertType($data, $format)
    {
        switch ($format) {
            case 'string' :
                $data = (string) $data;
                break;

            case 'int' :
                $data = (int) $data;
                break;

            case 'object' :
                $data = (object) $data;
                break;

            case 'array' :
                $data = (array) $data;
                break;

            case 'float' :
                $data = (float) $data;
                break;

            case 'mongoid':
                $data = (empty($data)) ? null : new \MongoId((string) $data);
                break;

            case null :
            default :
                break;
        }

        return $data;
    }

    public static function arrayKeyRequired($key, array $data)
    {

        if (is_array($key)) {

            $missing_keys = array();

            foreach ($key as $key) {

                if (!array_key_exists($key, $data)) {

                    $missing_keys[] = $key;

                }
            }


            if (!empty($missing_keys)) {

                $missing_keys = implode(',', $missing_keys);
                
                throw new ArrayKeyRequiredException($missing_keys);
            }
        } elseif (!array_key_exists($key, $data)) {
            throw new ArrayKeyRequiredException($key);
        }
    }

    /**
     * This calculation is assumed the earth is flat instead of sphere.
     */
    public static function getSquareBoundary($latitude, $longitude, $distanceInMeter)
    {
        if ($latitude > 90 or $latitude < -90 or $longitude > 180 or $longitude < -180) {
            throw new \Exception('Invalid latitude/longitude.');
        }

        if ($distanceInMeter < 0) {
            throw new \Exception('Distance must greater than 0 meter.');
        }

        if ($distanceInMeter > self::MAX_DISTANCE_IN_METER) {
            throw new \Exception('Distance must less than '.self::MAX_DISTANCE_IN_METER.' meter.');
        }

        $ne_lng = $longitude + ($distanceInMeter * self::DEGREE_PER_METER);
        $ne_lat = $latitude + ($distanceInMeter * self::DEGREE_PER_METER);

        $sw_lng = $longitude - ($distanceInMeter * self::DEGREE_PER_METER);
        $sw_lat = $latitude - ($distanceInMeter * self::DEGREE_PER_METER);

        return array(
            'ne' => array(
                'lng' => $ne_lng,
                'lat' => $ne_lat,
            ),
            'sw' => array(
                'lng' => $sw_lng,
                'lat' => $sw_lat,
            ),
        );
    }

    public static function reverseIpAddress($ip_address)
    {
        // get lat long from ip address if lat long not available.
        $url = 'https://geoip.seeties.me/geoip/index.php?ip='.$ip_address;
        $ip_reverse = self::webCrawler($url);
        // error_log(print_r($ip_reverse,1));
        /*
         * {
         * ip: "111.111.111.111",
         * country_code: "JP",
         * country: "Japan",
         * city: null,
         * postal: null,
         * latitude: 35.69,
         * longitude: 139.69
         * }
         */
        return json_decode($ip_reverse['content'], true);
    }

    public static function validateIpAddress($ip_address)
    {
        if (empty($ip_address)) {
            return false;
        }

        $data = array(
            'ip_address' => $ip_address,
        );

        $rules = array(
            'ip_address' => 'ip',
        );

        $validator = \Validator::make($data, $rules);

        if ($validator->fails()) {
            return false;
        }

        return true;
    }

    /**
     * @param type $filename
     * @param type $target_width
     * @param type $target_height
     * @param type $proportional
     *                            if true, keep the original proportion.
     * @param type $keepsmall
     *                            if true, do not resize image that original size is smaller than target size.
     *
     * @return Resource or Boolean
     *
     * @throws Exception
     */
    public static function resizeImage($filename, $target_width = 0, $target_height = 0, $proportional = true, $keepsmall = true, $auto_crop = false)
    {
        if (!is_numeric($target_width)) {
            throw new Exception('$target_width must be a number.');
        } elseif ($target_width < 0) {
            throw new Exception('$target_width must not smaller than 0.');
        }

        if (!is_numeric($target_height)) {
            throw new Exception('$target_height must be a number.');
        } elseif ($target_height < 0) {
            throw new Exception('$target_height must not smaller than 0.');
        }

        if ($target_width <= 0 and $target_height <= 0) {
            throw new Exception('Either $target_width or $target_height must greater than 0.');
        }

        $img_info = getimagesize(realpath($filename));

        switch ($img_info[2]) {
            case IMAGETYPE_GIF :
                $image = imagecreatefromgif(realpath($filename));
                break;
            case IMAGETYPE_JPEG :
                $image = imagecreatefromjpeg(realpath($filename));
                break;
            case IMAGETYPE_PNG :
                $image = imagecreatefrompng(realpath($filename));
                break;
            default :
                // if cannot retrieve image info, exit false
                return false;
        }

        // fix photos taken on cameras that have incorrect dimensions
        if ($img_info[2] == IMAGETYPE_JPEG) {
            @$exif = exif_read_data(realpath($filename));
        }

        $final_width = 0;
        $final_height = 0;
        list($width_old, $height_old) = $img_info;

        // get the orientation
        if (!empty($exif['Orientation'])) {
            $orientation = $exif['Orientation'];

            // apply fix for wrong orientation
            switch ($orientation) {
                case 3 :
                    $image = imagerotate($image, 180, 0);
                    break;
                case 6 :
                    $image = imagerotate($image, -90, 0);
                    list($width_old, $height_old) = array(
                        $height_old,
                        $width_old,
                    );
                    break;
                case 8 :
                    $image = imagerotate($image, 90, 0);
                    list($width_old, $height_old) = array(
                        $height_old,
                        $width_old,
                    );
                    break;
            }
        }

        if ($proportional) {
            if ($target_width == 0) {
                $factor = $target_height / $height_old;
            } elseif ($target_height == 0) {
                $factor = $target_width / $width_old;
            } else {
                $factor = min($target_width / $width_old, $target_height / $height_old);
            }

            $final_width = round($width_old * $factor);
            $final_height = round($height_old * $factor);
        } else {
            $final_width = ($target_width <= 0) ? $width_old : $target_width;
            $final_height = ($target_height <= 0) ? $height_old : $target_height;
        }

        // if both original width and height are smaller than target size, return the original file
        if ($keepsmall and ($width_old <= $final_width and $height_old <= $final_height)) {
            $final_width = $width_old;
            $final_height = $height_old;
        }

        $image_resized = imagecreatetruecolor($final_width, $final_height);

        if (($img_info[2] == IMAGETYPE_GIF) || ($img_info[2] == IMAGETYPE_PNG)) {
            $trnprt_indx = imagecolortransparent($image);

            // If we have a specific transparent color
            if ($trnprt_indx >= 0) {
                // Get the original image's transparent color's RGB values
                @$trnprt_color = imagecolorsforindex($image, $trnprt_indx);

                // Allocate the same color in the new image resource
                $trnprt_indx = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);

                // Completely fill the background of the new image with allocated color.
                imagefill($image_resized, 0, 0, $trnprt_indx);

                // Set the background color for new image to transparent
                imagecolortransparent($image_resized, $trnprt_indx);
            } // Always make a transparent background color for PNGs that don't have one allocated already
            elseif ($img_info[2] == IMAGETYPE_PNG) {
                // Turn off transparency blending (temporarily)
                imagealphablending($image_resized, false);

                // Create a new transparent color for image
                // $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                $color = imagecolorallocate($image_resized, 255, 255, 255);

                // Completely fill the background of the new image with allocated color.
                imagefill($image_resized, 0, 0, $color);

                // Restore transparency blending
                imagesavealpha($image_resized, true);
                imagealphablending($image_resized, true);
            }
        }

        $src_x = 0;
        $src_y = 0;
        if ($auto_crop) {
            $ratio_old = $width_old / $height_old;
            $ratio_new = $target_width / $target_height;

            // if ratio_old < ratio_new = fixed width
            if ($ratio_old < $ratio_new) {
                $height_new = ceil($width_old * $target_height / $target_width);
                $src_y = ceil(($height_old - $height_new) / 2);
                $height_old = $height_new;
            } else {
                $width_new = ceil($height_old * $target_width / $target_height);
                $src_x = ceil(($width_old - $width_new) / 2);
                $width_old = $width_new;
            }
        }

        imagecopyresampled($image_resized, $image, 0, 0, $src_x, $src_y, $final_width, $final_height, $width_old, $height_old);
        imageinterlace($image_resized, 1);

        return $image_resized;
    }

    public static function json_decode($data, $field = '')
    {
        $result = json_decode($data, true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE :
                return $result;

            case JSON_ERROR_DEPTH :
            case JSON_ERROR_STATE_MISMATCH :
            case JSON_ERROR_CTRL_CHAR :
            case JSON_ERROR_SYNTAX :
            case JSON_ERROR_UTF8 :
                // throw new \Exception('Invalid JSON.' . (!empty($field) ? " ($field)" : ""));
                throw new \Exception('seeties.json.invalid');
                break;
        }
    }

    public static function gen_post($cnt)
    {
        $_3_month = 3 / 12 * 365 * 24 * 3600; // in seconds
        $_total_cat = 13;
        $_u_id = new \MongoId('52414114991d035d00e3cba0');
        $_dates = [];
        $ll = new LoremIpsumGenerator();

        if (!$cnt) {
            $cnt = 0;
        }

        for ($i = 0; $i < $cnt; ++$i) {
            $_dates[$i] = time() - (int) ($_3_month * mt_rand() / mt_getrandmax());
        }

        rsort($_dates);

        for ($i = 0; $i < $cnt; ++$i) {
            PostsModel::insert(array(
                'u_id' => $_u_id,
                'message' => $ll->getContent(55),
                'category' => rand(1, $_total_cat),
                'place_name' => '',
                'location' => self::createLocation(),
                'photos' => [],
                'link' => '',
                'created_at' => new \MongoDate($_dates[$i]),
                'updated_at' => new \MongoDate($_dates[$i]),
            ));
        }
    }

    public static function checkBadWord($word)
    {
        $bwd = new badWordDetector();

        return $bwd->censorString($word);
    }

    public static function checkBanWord($word)
    {
        $bwd = new badWordDetector();

        return $bwd->censorString2($word);
    }

    public static function createLocation($limitPlace = null)
    {
        $min_lat = 2.593806;
        $min_lng = 100.8086381;
        $max_lat = 3.868338;
        $max_lng = 101.970064;

        $max_try = 1000;

        while ($max_try--) {
            $lat = $min_lat + (mt_rand() / mt_getrandmax());
            $lng = $min_lng + (mt_rand() / mt_getrandmax());

            if ($lat > $max_lat or $lng > $max_lng) {
                continue;
            }

            $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true";

            $result = self::webCrawler($url);
            $json = json_decode($result['content'], true);
            if (!isset($json['results'][0])) {
                continue;
            }

            return $json['results'][0];
        }

        throw new \Exception("Tried 1000 times but still can't get a location.");
    }

    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }

    public static function isJSON($string)
    {
        return is_string($string) && is_object(json_decode($string)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public static function getDevicesTypeList()
    {
        $device_list = array(
            '1' => 'android',
            '2' => 'apple',
            '9' => 'web',
        );

        return $device_list;
    }

    public static function isValidDeviceType($device_type)
    {
        $device_list = self::getDevicesTypeList();

        if (empty($device_list[$device_type])) {
            return false;
        }

        return true;
    }

    public static function isWebDeviceAccess()
    {
        $user = Auth::user();

        if ((!empty($user['device_type'])) && ($user['device_type'] == 9)) {
            return true;
        }

        return false;
    }

    public static function isEnglishOnlyString($string)
    {
        $is_english_only = true;

        if (strlen($string) != mb_strlen($string, 'utf-8')) {
            $is_english_only = false;
        }

        return $is_english_only;
    }

    public static function isValidTimestamp($timestamp)
    {
        $is_valid = ((string) (int) $timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX);

        return $is_valid;
    }

    /**
     * construct mongodb sort query.
     *
     * @param array  $sortable_fields sortable fields
     * @param string $sort_info       sorting info
     */
    public static function constructSortQuery(array $sortable_fields, $sort_info)
    {
        // +field/field = ascending, -field : descending
        $sort_query = array();

        if (empty($sort_info) || empty($sortable_fields)) {
            return $sort_query;
        }

        $sort_field_list = explode(',', $sort_info);

        if (empty($sort_field_list)) {
            return $sort_query;
        }

        foreach ($sort_field_list as $sort_field) {
            foreach ($sortable_fields as $sortable_field) {
                if ((strpos($sort_field, $sortable_field) === 0) || (strpos($sort_field, '+'.$sortable_field) === 0)) {
                    $sort_query[$sortable_field] = 1;
                } elseif (strpos($sort_field, '-'.$sortable_field) === 0) {
                    $sort_query[$sortable_field] = -1;
                }
            }
        }

        return $sort_query;
    }

    /**
     * set eloquent order by query.
     *
     * @param object $model   model object
     * @param array  $sort_by sorting info
     */
    public static function setEloquentOrderByQuery($model, array $sort_by)
    {
        if (!is_object($model)) {
            return false;
        }

        if (!empty($sort_by)) {
            foreach ($sort_by as $sort_field => $sort_order) {
                $order = ($sort_order > 0) ? 'asc' : 'desc';
                $model = $model->orderBy($sort_field, $order);
            }
        }

        return $model;
    }

    public static function constructFilterQuery(array $filterable_fields, $filter_info)
    {
        // field='>value'  : field greater than value
        // field='>=value' : field equal or greater than value
        // field='value'   : field exactly equal to value
        // field='<value'  : field less than value
        // field='<=value' : field equal or less than value
        $filter_query = array();

        if (empty($filter_info) || empty($filterable_fields)) {
            return $filter_query;
        }

        $filter_field_list = array_intersect_key($filter_info, $filterable_fields);

        foreach ($filter_field_list as $filter_field => $filter_field_value) {
            $values = explode(',', $filter_field_value);

            if (empty($values)) {
                continue;
            }

            // validate the type
            $value_type = $filterable_fields[$filter_field];
            $value_query = array();

            foreach ($values as $value) {
                if (empty($value)) {
                    continue;
                }

                switch ($value_type) {
                    default:
                    case 'string':
                        $value_query['$in'][] = (string) $value;
                        break;

                    case 'integer':
                        $value_query['$in'][] = (int) $value;
                        break;

                    case 'rfc3339_datetime':
                        // format = Y-m-d\TH:i:sP or 2015-02-28T12:12:12-02:00
                        $datetime_format = 'Y-m-d\TH:i:sP';

                        $operator = self::convertQueryOperatorString($value);

                        if (($operator) && (self::validateDateTimeFormat($value, $datetime_format))) {
                            $value_query[$operator] = new \MongoDate(strtotime($value));
                        }

                        break;
                }
            }

            if (!empty($value_query)) {
                $filter_query[$filter_field] = $value_query;
            }
        }

        return $filter_query;
    }

    public static function validateDateTimeFormat($datetime, $datetime_format)
    {
        $converted_datetime = \DateTime::createFromFormat($datetime_format, $datetime);

        return $converted_datetime && ($converted_datetime->format($datetime_format) == $datetime);
    }

    public static function aasort($array, $key)
    {
        $sorter = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }
        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }
        $array = $ret;

        return $array;
    }

    private static function convertQueryOperatorString(&$value)
    {
        $valid_operators = array(
            '>=' => '$gte',
            '>' => '$gt',
            '<=' => '$lte',
            '<' => '$lt',
        );

        $return_operator = false;

        foreach ($valid_operators as $valid_operator => $converted_operator) {
            if (strpos($value, $valid_operator) === 0) {
                $return_operator = $converted_operator;
                $value = str_replace($valid_operator, '', $value);
                break;
            }
        }

        return $return_operator;
    }
}

class badWordDetector
{
    /**
     * Generates a random string.
     *
     * @param string $chars
     *                      Chars that can be used.
     * @param int    $len
     *                      Length of the output string.
     *                      string
     */
    public function randCensor($chars, $len)
    {
        mt_srand(); // useful for < PHP4.2
        $lastChar = strlen($chars) - 1;
        $randOld = -1;
        $out = '';

        // create $len chars
        for ($i = $len; $i > 0; --$i) {
            // generate random char - it must be different from previously generated
            while (($randNew = mt_rand(0, $lastChar)) === $randOld) {
            }
            $randOld = $randNew;
            $out .= $chars[$randNew];
        }

        return $out;
    }

    /**
     * Apply censorship to $string, replacing $badwords with $censorChar.
     *
     * @param string      $string
     *                                String to be censored.
     * @param string[int] $badwords
     *                                Array of badwords.
     * @param string      $censorChar
     *                                String which replaces bad words. If it's more than 1-char long,
     *                                a random string will be generated from these chars. Default: '*'
     *                                string[string]
     */
    public function censorString($string, $censorChar = '*')
    {
        include app_path().'/Seeties/Includes/wordlist-regex.php';
        $leet_replace = array();
        $leet_replace['a'] = '(a|a\.|a\-|4|@|Á|á|À|Â|à|Â|â|Ä|ä|Ã|ã|Å|å|α|Δ|Λ|λ)';
        $leet_replace['b'] = '(b|b\.|b\-|8|\|3|ß|Β|β)';
        $leet_replace['c'] = '(c|c\.|c\-|Ç|ç|¢|€|<|\(|{|©)';
        $leet_replace['d'] = '(d|d\.|d\-|&part;|\|\)|Þ|þ|Ð|ð)';
        $leet_replace['e'] = '(e|e\.|e\-|3|€|È|è|É|é|Ê|ê|∑)';
        $leet_replace['f'] = '(f|f\.|f\-|ƒ)';
        $leet_replace['g'] = '(g|g\.|g\-|6|9)';
        $leet_replace['h'] = '(h|h\.|h\-|Η)';
        $leet_replace['i'] = '(i|i\.|i\-|!|\||\]\[|]|1|∫|Ì|Í|Î|Ï|ì|í|î|ï)';
        $leet_replace['j'] = '(j|j\.|j\-)';
        $leet_replace['k'] = '(k|k\.|k\-|Κ|κ)';
        $leet_replace['l'] = '(l|1\.|l\-|!|\||\]\[|]|£|∫|Ì|Í|Î|Ï)';
        $leet_replace['m'] = '(m|m\.|m\-)';
        $leet_replace['n'] = '(n|n\.|n\-|η|Ν|Π)';
        $leet_replace['o'] = '(o|o\.|o\-|0|Ο|ο|Φ|¤|°|ø)';
        $leet_replace['p'] = '(p|p\.|p\-|ρ|Ρ|¶|þ)';
        $leet_replace['q'] = '(q|q\.|q\-)';
        $leet_replace['r'] = '(r|r\.|r\-|®)';
        $leet_replace['s'] = '(s|s\.|s\-|5|\$|§)';
        $leet_replace['t'] = '(t|t\.|t\-|Τ|τ)';
        $leet_replace['u'] = '(u|u\.|u\-|υ|µ)';
        $leet_replace['v'] = '(v|v\.|v\-|υ|ν)';
        $leet_replace['w'] = '(w|w\.|w\-|ω|ψ|Ψ)';
        $leet_replace['x'] = '(x|x\.|x\-|Χ|χ)';
        $leet_replace['y'] = '(y|y\.|y\-|¥|γ|ÿ|ý|Ÿ|Ý)';
        $leet_replace['z'] = '(z|z\.|z\-|Ζ)';

        $words = explode(' ', $string);

        // is $censorChar a single char?
        $isOneChar = (strlen($censorChar) === 1);

        for ($x = 0; $x < count($badwords); ++$x) {
            $replacement[$x] = $isOneChar ? str_repeat($censorChar, strlen($badwords[$x])) : randCensor($censorChar, strlen($badwords[$x]));

            $badwords[$x] = '/'.str_ireplace(array_keys($leet_replace), array_values($leet_replace), $badwords[$x]).'/i';
        }

        $newstring = array();
        $newstring['orig'] = html_entity_decode($string);
        $newstring['clean'] = preg_replace($badwords, $replacement, $newstring['orig']);

        if ($newstring['orig'] != $newstring['clean']) {
            return $newstring['clean'];
        }

        return true;
    }

    public function censorString2($string, $censorChar = '*')
    {
        include app_path().'/Seeties/Includes/wordlist-regex.php';

        if (in_array($string, $banwords)) {
            return $string;
        }

        return true;
    }
}

// class ArrayKeyRequiredException extends \Exception
// {
//     // the missing key that need to be in the array
//     public $key = '';

//     public function __construct($key, $code = 0, \Exception $previous = null)
//     {
//         $message = "missing key '$key'.";
//         parent::__construct($message, $code, $previous);

//         $this->key = $key;
//     }
// }

class LoremIpsumGenerator
{
    /**
     * Copyright (c) 2009, Mathew Tinsley (tinsley@tinsology.net)
     * All rights reserved.
     *
     * Redistribution and use in source and binary forms, with or without
     * modification, are permitted provided that the following conditions are met:
     * * Redistributions of source code must retain the above copyright
     * notice, this list of conditions and the following disclaimer.
     * * Redistributions in binary form must reproduce the above copyright
     * notice, this list of conditions and the following disclaimer in the
     * documentation and/or other materials provided with the distribution.
     * * Neither the name of the organization nor the
     * names of its contributors may be used to endorse or promote products
     * derived from this software without specific prior written permission.
     *
     * THIS SOFTWARE IS PROVIDED BY MATHEW TINSLEY ''AS IS'' AND ANY
     * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
     * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
     * DISCLAIMED. IN NO EVENT SHALL <copyright holder> BE LIABLE FOR ANY
     * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
     * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
     * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
     * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
     * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
     * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     */
    private $words, $wordsPerParagraph, $wordsPerSentence;

    public function __construct($wordsPer = 100)
    {
        $this->wordsPerParagraph = $wordsPer;
        $this->wordsPerSentence = 24.460;
        $this->words = array(
            'lorem',
            'ipsum',
            'dolor',
            'sit',
            'amet',
            'consectetur',
            'adipiscing',
            'elit',
            'curabitur',
            'vel',
            'hendrerit',
            'libero',
            'eleifend',
            'blandit',
            'nunc',
            'ornare',
            'odio',
            'ut',
            'orci',
            'gravida',
            'imperdiet',
            'nullam',
            'purus',
            'lacinia',
            'a',
            'pretium',
            'quis',
            'congue',
            'praesent',
            'sagittis',
            'laoreet',
            'auctor',
            'mauris',
            'non',
            'velit',
            'eros',
            'dictum',
            'proin',
            'accumsan',
            'sapien',
            'nec',
            'massa',
            'volutpat',
            'venenatis',
            'sed',
            'eu',
            'molestie',
            'lacus',
            'quisque',
            'porttitor',
            'ligula',
            'dui',
            'mollis',
            'tempus',
            'at',
            'magna',
            'vestibulum',
            'turpis',
            'ac',
            'diam',
            'tincidunt',
            'id',
            'condimentum',
            'enim',
            'sodales',
            'in',
            'hac',
            'habitasse',
            'platea',
            'dictumst',
            'aenean',
            'neque',
            'fusce',
            'augue',
            'leo',
            'eget',
            'semper',
            'mattis',
            'tortor',
            'scelerisque',
            'nulla',
            'interdum',
            'tellus',
            'malesuada',
            'rhoncus',
            'porta',
            'sem',
            'aliquet',
            'et',
            'nam',
            'suspendisse',
            'potenti',
            'vivamus',
            'luctus',
            'fringilla',
            'erat',
            'donec',
            'justo',
            'vehicula',
            'ultricies',
            'varius',
            'ante',
            'primis',
            'faucibus',
            'ultrices',
            'posuere',
            'cubilia',
            'curae',
            'etiam',
            'cursus',
            'aliquam',
            'quam',
            'dapibus',
            'nisl',
            'feugiat',
            'egestas',
            'class',
            'aptent',
            'taciti',
            'sociosqu',
            'ad',
            'litora',
            'torquent',
            'per',
            'conubia',
            'nostra',
            'inceptos',
            'himenaeos',
            'phasellus',
            'nibh',
            'pulvinar',
            'vitae',
            'urna',
            'iaculis',
            'lobortis',
            'nisi',
            'viverra',
            'arcu',
            'morbi',
            'pellentesque',
            'metus',
            'commodo',
            'ut',
            'facilisis',
            'felis',
            'tristique',
            'ullamcorper',
            'placerat',
            'aenean',
            'convallis',
            'sollicitudin',
            'integer',
            'rutrum',
            'duis',
            'est',
            'etiam',
            'bibendum',
            'donec',
            'pharetra',
            'vulputate',
            'maecenas',
            'mi',
            'fermentum',
            'consequat',
            'suscipit',
            'aliquam',
            'habitant',
            'senectus',
            'netus',
            'fames',
            'quisque',
            'euismod',
            'curabitur',
            'lectus',
            'elementum',
            'tempor',
            'risus',
            'cras',
        );
    }

    public function getContent($count, $format = 'plain', $loremipsum = true)
    {
        $format = strtolower($format);

        if ($count <= 0) {
            return '';
        }

        switch ($format) {
            case 'txt' :
                return $this->getText($count, $loremipsum);
            case 'plain' :
                return $this->getPlain($count, $loremipsum);
            default :
                return $this->getHTML($count, $loremipsum);
        }
    }

    private function getWords(&$arr, $count, $loremipsum)
    {
        $i = 0;
        if ($loremipsum) {
            $i = 2;
            $arr[0] = 'lorem';
            $arr[1] = 'ipsum';
        }

        for ($i; $i < $count; ++$i) {
            $index = array_rand($this->words);
            $word = $this->words[$index];
            // echo $index . '=>' . $word . '<br />';

            if ($i > 0 && $arr[$i - 1] == $word) {
                --$i;
            } else {
                $arr[$i] = $word;
            }
        }
    }

    private function getPlain($count, $loremipsum, $returnStr = true)
    {
        $words = array();
        $this->getWords($words, $count, $loremipsum);
        // print_r($words);

        $delta = $count;
        $curr = 0;
        $sentences = array();
        while ($delta > 0) {
            $senSize = $this->gaussianSentence();
            // echo $curr . '<br />';
            if (($delta - $senSize) < 4) {
                $senSize = $delta;
            }

            $delta -= $senSize;

            $sentence = array();
            for ($i = $curr; $i < ($curr + $senSize); ++$i) {
                $sentence[] = $words[$i];
            }

            $this->punctuate($sentence);
            $curr = $curr + $senSize;
            $sentences[] = $sentence;
        }

        if ($returnStr) {
            $output = '';
            foreach ($sentences as $s) {
                foreach ($s as $w) {
                    $output .= $w.' ';
                }
            }

            return $output;
        } else {
            return $sentences;
        }
    }

    private function getText($count, $loremipsum)
    {
        $sentences = $this->getPlain($count, $loremipsum, false);
        $paragraphs = $this->getParagraphArr($sentences);

        $paragraphStr = array();
        foreach ($paragraphs as $p) {
            $paragraphStr[] = $this->paragraphToString($p);
        }

        $paragraphStr[0] = "\t".$paragraphStr[0];

        return implode("\n\n\t", $paragraphStr);
    }

    private function getParagraphArr($sentences)
    {
        $wordsPer = $this->wordsPerParagraph;
        $sentenceAvg = $this->wordsPerSentence;
        $total = count($sentences);

        $paragraphs = array();
        $pCount = 0;
        $currCount = 0;
        $curr = array();

        for ($i = 0; $i < $total; ++$i) {
            $s = $sentences[$i];
            $currCount += count($s);
            $curr[] = $s;
            if ($currCount >= ($wordsPer - round($sentenceAvg / 2.00)) || $i == $total - 1) {
                $currCount = 0;
                $paragraphs[] = $curr;
                $curr = array();
                // print_r($paragraphs);
            }
            // print_r($paragraphs);
        }

        return $paragraphs;
    }

    private function getHTML($count, $loremipsum)
    {
        $sentences = $this->getPlain($count, $loremipsum, false);
        $paragraphs = $this->getParagraphArr($sentences);
        // print_r($paragraphs);

        $paragraphStr = array();
        foreach ($paragraphs as $p) {
            $paragraphStr[] = "<p>\n".$this->paragraphToString($p, true).'</p>';
        }

        // add new lines for the sake of clean code
        return implode("\n", $paragraphStr);
    }

    private function paragraphToString($paragraph, $htmlCleanCode = false)
    {
        $paragraphStr = '';
        foreach ($paragraph as $sentence) {
            foreach ($sentence as $word) {
                $paragraphStr .= $word.' ';
            }

            if ($htmlCleanCode) {
                $paragraphStr .= "\n";
            }
        }

        return $paragraphStr;
    }

    /*
     * Inserts commas and periods in the given
     * word array.
     */
    private function punctuate(&$sentence)
    {
        $count = count($sentence);
        $sentence[$count - 1] = $sentence[$count - 1].'.';

        if ($count < 4) {
            return $sentence;
        }

        $commas = $this->numberOfCommas($count);

        for ($i = 1; $i <= $commas; ++$i) {
            $index = (int) round($i * $count / ($commas + 1));

            if ($index < ($count - 1) && $index > 0) {
                $sentence[$index] = $sentence[$index].',';
            }
        }
    }

    /*
     * Determines the number of commas for a
     * sentence of the given length. Average and
     * standard deviation are determined superficially
     */
    private function numberOfCommas($len)
    {
        $avg = (float) log($len, 6);
        $stdDev = (float) $avg / 6.000;

        return (int) round($this->gauss_ms($avg, $stdDev));
    }

    /*
     * Returns a number on a gaussian distribution
     * based on the average word length of an english
     * sentence.
     * Statistics Source:
     * http://hearle.nahoo.net/Academic/Maths/Sentence.html
     * Average: 24.46
     * Standard Deviation: 5.08
     */
    private function gaussianSentence()
    {
        $avg = (float) 24.460;
        $stdDev = (float) 5.080;

        return (int) round($this->gauss_ms($avg, $stdDev));
    }

    /*
     * The following three functions are used to
     * compute numbers with a guassian distrobution
     * Source:
     * http://us.php.net/manual/en/function.rand.php#53784
     */
    private function gauss()
    { // N(0,1)
                               // returns random number with normal distribution:
                               // mean=0
                               // std dev=1
                               // auxilary vars
        $x = $this->random_0_1();
        $y = $this->random_0_1();

        // two independent variables with normal distribution N(0,1)
        $u = sqrt(-2 * log($x)) * cos(2 * pi() * $y);
        $v = sqrt(-2 * log($x)) * sin(2 * pi() * $y);

        // i will return only one, couse only one needed
        return $u;
    }

    private function gauss_ms($m = 0.0, $s = 1.0)
    {
        return $this->gauss() * $s + $m;
    }

    private function random_0_1()
    {
        return (float) rand() / (float) getrandmax();
    }
}

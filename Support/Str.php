<?php

class Str {

    public static $string = '';

    /**
     * @param string $string
     * @param string $divider
     * @return string
     */
    public static function slug(string $string, string $divider = '-') {

        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );

        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );

        $string = trim($string, $divider);

        $string = preg_replace($search, $replace, $string);

        $string = preg_replace('/(-)+/', '-', $string);

        $string = strtolower($string);
        
        return $string;
    }


    /**
     * @param string $string
     * @return string
     */
    public static function removeVietnameseTones(string $string = '') {

        $string = preg_replace("/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/","a",$string); 
        $string = preg_replace("/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/","e", $string); 
        $string = preg_replace("/ì|í|ị|ỉ|ĩ/","i", $string); 
        $string = preg_replace("/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/","o", $string); 
        $string = preg_replace("/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/","u", $string); 
        $string = preg_replace("/ỳ|ý|ỵ|ỷ|ỹ/","y", $string); 
        $string = preg_replace("/đ/","d", $string);
        $string = preg_replace("/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/", "A", $string);
        $string = preg_replace("/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/", "E", $string);
        $string = preg_replace("/Ì|Í|Ị|Ỉ|Ĩ/", "I", $string);
        $string = preg_replace("/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/", "O", $string);
        $string = preg_replace("/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/", "U", $string);
        $string = preg_replace("/Ỳ|Ý|Ỵ|Ỷ|Ỹ/", "Y", $string);
        $string = preg_replace("/Đ/", "D", $string);
        
        /**
         *  Some system encode vietnamese combining accent as individual utf-8 characters
         *  Một vài bộ encode coi các dấu mũ, dấu chữ như một kí tự riêng biệt nên thêm hai dòng này
         */
        $string = preg_replace("/\u0300|\u0301|\u0303|\u0309|\u0323/", "", $string); // ̀ ́ ̃ ̉ ̣  huyền, sắc, ngã, hỏi, nặng
        $string = preg_replace("/\u02C6|\u0306|\u031B/", "", $string); // ˆ ̆ ̛  Â, Ê, Ă, Ơ, Ư
        
        /**
         * Remove extra spaces
         */
        $string = preg_replace("/ + /"," ", $string);
        $string = trim($string, " ");
        
        /**
         * Remove punctuations
         */
        $string = preg_replace("/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\//"," ", $string);
        
        return strtolower($string);
    }


    /**
     * Task: method generates a random string of the specified length
     * @param string $length 
     * @param string $type
     */
    public static function random($length = 10, $type = "string") {

        $characters = null;

        switch ($type) {

            case "string":

                $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                break;

            case "int":
                $characters = "0123456789";
                break;

            default:
                $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                break;
        }

        $charactersLength = strlen($characters);

        $randomString = '';

        for ($i = 0; $i < $length; $i++) {

            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @param string $string
     * @return Integer
     */
    public static function length(string $string = '') {

        return strlen(trim($string));
    }


    /**
     * @param string $string
     * @param Integer $position
     * @return string $replace
     */
    public static function limit(string $string = '', $position = 0, string $replace = '') {

        $string = str_replace(substr($string, $position), "", $string) . $replace;

        return trim($string);
    }


    /**
     * @param string $string
     */
    public static function lower(string $string = '') {

        return strtolower(trim($string));
    }


    /**
     * @param string $string
     */
    public static function upper(string $string = '') {

        return strtoupper(trim($string));
    }


    /**
     * @param string $search
     * @param string $replace
     * @param string|array $subject
     * @return string
     */
    public static function replace(string $search = null, string $replace = null, $subject = null) {

        if(is_null($search) || is_null($replace) || is_null($subject)) return '';

        return str_replace($search, $replace, $subject);
    }

    /**
     * Task: method removes the given value or array of values from the string
     * @param string $search
     * @param string $subject
     * @return string
     */
    public static function remove(string $search, string $subject) {

        return self::replace($search, '', $subject);
    }

    /**
     * Task: method reverses the given string:
     * @param string $string
     * @return string
     */
    public static function reverse(string $string = null) {

        if(is_null($string)) return '';

        return strrev($string);
    }

    /**
     * Task: method determines if the given string contains the given value. This method is case sensitive
     * You may also pass an array of values to determine if the given string contains any of the values in the array
     * 
     * @param string $haystack
     * @param string|array $needle
     * @return boolean
     */
    public static function contains(string $haystack = null, $needle = null) {

        if(is_null($haystack) || is_null($needle)) return false;

        if(is_array($needle)) {

            $i = 0;

            $f = false;

            while($i < count($needle)) {

                if(!is_string($needle[$i])) {

                    $f  = false;

                    break;
                }

                if(empty($needle[$i])) {

                    $f  = true;

                    $i++;
                    
                    continue;
                }

                if (strpos($haystack, $needle[$i]) !== false) {

                    $f  = true;

                    break;
                }

                $i++;
            }

            return $f;
        }

        if(empty($needle)) {

            return true;
        }
        
        if (strpos($haystack, $needle) !== false) {

            return true;
        }

        /**
         * PHP version >=8
         * return str_contains($haystack, $needle);
         */

        return false;
    }


    /**
     * Task: method adds a single instance of the given value to a string if it does not already end with that value
     * @param string $string
     * @param string $end
     * @return string
     */
    public static function finish(string $string = null, string $end = null) {

        if(is_null($string)) {

            return '';
        }

        if(is_null($end) || empty($end)) {

            return $string;
        }

        if(substr($string, -1) == $end) {

            return $string;
        }

        return $string . $end;
    }

    /**
     * Task: method adds a single instance of the given value to a string if it does not already start with that value
     * @param string $string
     * @param string $start
     * @return string
     */
    public static function start(string $string = null, string $start = null) {

        if(is_null($string)) {

            return '';
        }

        if(is_null($start) || empty($start)) {

            return $string;
        }

        if(substr($string, 0, 1) == $start) {

            return $string;
        }

        return $start . $string;
    }

    //-------------------------------------Of Object-----------------------------------
    /**
     * @param string $string
     * @return Object
     */
    public static function of(string $string = '') {

        self::$string = $string;
        
        return new self;
    }

    /**
     * Task: Determines if the given string is an exact match with another string:
     * @param string $string
     * @return boolean
     */
    public function exactly(string $string = '') {
        
        if($string === self::$string) {

            return true;
        }

        return false;
    }

    /**
     * Task: The "explode" method splits the string by the given delimiter and returns a collection containing each section of the split string
     * @param string $separator
     * @return array
     */
    public function explode($separator = null) {

        if(is_null($separator)) {

            return [];
        }

        return explode($separator, self::$string);
    }
}
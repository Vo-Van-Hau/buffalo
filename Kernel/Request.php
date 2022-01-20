<?php

class Request {

    /**
     * Retrieve an input item from the request.
     *
     * @param  string|null  $key
     * @return mixed
     */
    public function input($key = null) {

        if(is_null($key)) return null;
        
        if(isset($_POST[$key]) && $_POST[$key]) {

            return trim($_POST[$key]);
        }
        if(isset($_GET[$key]) && $_GET[$key]) {

            return trim($_GET[$key]);
        }
 
        return null;
    }

    /**
     * All cookies created by the this framework are encrypted and signed with an authentication code, 
     * meaning they will be considered invalid if they have been changed by the client. 
     * To retrieve a cookie value from the request, use the cookie method 
     * 
     * @param string $name
     * @return mixed
     */
    public function cookie($name = null) {

        if(is_null($name)) return null;

        if(isset($_COOKIE[$name])) {

            return Crypt::decryptString($_COOKIE[$name]);
        }

        return null;
    }
}

<?php

class Request {

    /**
     * Retrieve an input item from the request.
     *
     * @param  string|null $name
     * @return mixed
     */
    public function input($name = null) {

        if(is_null($name)) return null;
        
        if(isset($_POST[$name]) && $_POST[$name]) {

            return trim($_POST[$name]);
        }
        if(isset($_GET[$name]) && $_GET[$name]) {

            return trim($_GET[$name]);
        }

        if(isset($_FILES[$name]) && $_FILES[$name]) {

            return $_FILES[$name];
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

<?php

class CookieCore {

    /**
     * Using PHP's native setcookie method
     * All cookies created by the this framework are encrypted and signed with an authentication code, 
     * meaning they will be considered invalid if they have been changed by the client
     */
    public function __setCookie($name, $value, $expires_time, $path, $domain, $secure, $httpOnly) {

        setcookie($name, $value, $expires_time, $path, $domain, $secure, $httpOnly);
    }
}
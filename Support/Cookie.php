<?php

class Cookie {

    public static $request;

    public static $cookie;

    public function __construct(Request $request, CookieCore $cookie) {

        self::$cookie   = $cookie;
        
        self::$request  = $request;
    }

    /**
     * If you would like to ensure that a cookie is sent with the outgoing response but you do not yet have an instance of that response, 
     * you can use the Cookie facade to "queue" cookies for attachment to the response when it is sent. 
     * The queue method accepts the arguments needed to create a cookie instance. These cookies will be attached to the outgoing response before it is sent to the browser
     * 
     * @param string $name
     * @param string $value
     * @param array $minutes
     * @param string $path
     * @param string $domain
     * @param string $secure
     * @param string $httpOnly
     * @return void()
     */
    public static function queue(string $name, string $value, string $minutes, string $path = '/', string $domain = null, string $secure = null, string $httpOnly = null) {

        self::$cookie->__setCookie($name, Crypt::encryptString($value), time() + ($minutes*60), $path, $domain, $secure, $httpOnly);
    }

    /**
     * Determine if a cookie exists on the request.
     *
     * @param  string  $key
     * @return bool
     */
    public static function has($key) {

        return ! is_null(self::$request->cookie($key));
    }

    /**
     * Retrieve a cookie from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return string|null
     */
    public static function get($key = null) {

        return self::$request->cookie($key);
    }
}
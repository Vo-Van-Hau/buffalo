<?php

class Session {

    public static $session;

    public function __construct(SessionCore $session) {

        self::$session = $session;
    }

    public static function get(string $name = null) {

        return self::$session->get($name);
    }

    public static function set(string $name = null, $value = null) {

        self::$session->set($name, $value);
    }
}
<?php

class Session {

    public static $session;

    public function __construct(SessionCore $session) {

        self::$session = $session;
    }

    /**
     * Returns the session ID.
     *
     * @return string
     */
    public function getId() {

        return self::$session->getId();
    }

    /**
     * @return void()|false
     */
    public function setId(string $id = null) {

        return self::$session->setId($id);
    }

    /**
     * @return string
     */
    public function getName() {

        return self::$session->getName();
    }

    /**
     * @return void()|false
     */
    public function setName(string $name = null) {

        return self::$session->setName($name);
    }

    /**
     * @return String
     */
    public static function get(string $name = null) {

        return self::$session->get($name);
    }

    /**
     * @return void()
     */
    public static function set(string $name = null, $value = null) {

        self::$session->set($name, $value);
    }

    /**
     * @return void()
     */
    public static function remove(string $name = null) {

        self::$session->remove($name);
    }

    /**
     * @return void()
     */
    public static function clear() {

        self::$session->clear();
    }

    /**
     * @return boolean
     */
    public static function has($name = null) {

        return self::$session->has($name);
    }

    /**
     * @return array
     */
    public static function all() {

        return self::$session->all();
    }

    /**
     * @return Integer
     */
    public static function count() {

        return self::$session->count();
    }

    /**
     * @return boolean
     */
    public static function replace(array $attributes) {

        return self::$session->replace($attributes);
    }

    /**
     * @return boolean
     */
    public static function flash($name, $value) {

        return self::$session->flash($name, $value);
    }
}
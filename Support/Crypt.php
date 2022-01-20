<?php

class Crypt {

    public static $encrypter;

    public function __construct(Encrypter $encrypter) {

        self::$encrypter = $encrypter;
    }

    /**
     * You may encrypt a value using the encryptString method provided by the Encrypter.
     * @param string $value
     * return mixed
     */
    public static function encryptString($value = null) {

        if(is_null($value)) return '';

        return self::$encrypter->encryptString($value);
    }

    /**
     * You may decrypt values using the decryptString method provided by the Encrypter
     * @param string $encryptedValue
     * return mixed
     */
    public static function decryptString($encryptedValue = null) {

        if(is_null($encryptedValue)) return '';

        return self::$encrypter->decryptString($encryptedValue);
    }
}
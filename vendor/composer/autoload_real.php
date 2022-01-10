<?php

class ComposerAutoloaderInit {

    private static $loader;

    public static function loadClassLoader($class) {

        if ('Composer\Autoload\ClassLoader' === $class) {

            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader(){

        if (null !== self::$loader) {

            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit', 'loadClassLoader'), true, true);

        /**
         * $dirname(\dirname(__FILE__) as parameter for __constructor($vendorDir = null) in ClassLoader::class
         */
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(\dirname(__FILE__)));

        spl_autoload_unregister(array('ComposerAutoloaderInit', 'loadClassLoader'));
        
        $classMap = require __DIR__ . '/autoload_classmap.php';

        if ($classMap) {

            $loader->addClassMap($classMap);
        }

        $loader->register(true);

        return $loader;
    }
}
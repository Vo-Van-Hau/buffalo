<?php

/**
 * $baseDir: return base folder
 */
$baseDir = dirname(dirname(__FILE__));

$mulViewDir = array(

    "resources.views" => $baseDir . "/resources/views/",
);

$URL = [
    'protocol'      => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://',
    'host'          => $_SERVER['HTTP_HOST'],
    'name'          => $_SERVER['SERVER_NAME'],
    'port'          => $_SERVER['SERVER_PORT'],
];




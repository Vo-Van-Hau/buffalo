<?php

/**
 * $baseDir: return base folder
 */
$baseDir = dirname(dirname(__FILE__));

$viewConfig = require_once($baseDir . "/config/view.php");

$multipleViewDir = array(

    "resources.views" => $baseDir . $viewConfig['view_dir'],
);

$URL = [
    'protocol'      => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://',
    'host'          => $_SERVER['HTTP_HOST'],
    'name'          => $_SERVER['SERVER_NAME'],
    'port'          => $_SERVER['SERVER_PORT'],
];




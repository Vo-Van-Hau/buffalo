<?php

/**
 * Task: Redirect to a different page in the current directory that was requested
 * @param string $url
 * 
 */
function redirect(string $path = null) {

    global $URL;

    $path = trim($path);

    if(!isset($path) || !$path || is_null($path)) {

        return false;
    }

    if(substr($path, 0, 1) != "/") {

        $path = "/" . $path;
    }
    
    $redirect_to = $URL['protocol'] . $URL['host'] . $path;

    header("Location: {$redirect_to}");

    exit;
}

/**
 * Task: Refresh a page
 * @param Integer $delay
 */
function refresh($delay = 0) {

    header("Refresh: $delay;"); 

    exit;
}
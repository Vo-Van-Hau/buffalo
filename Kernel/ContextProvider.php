<?php

/**
 * Initialized Session
 */
session_start();

global $mulViewDir;

$template_v1    = new TemplateEngine($mulViewDir["resources.views"]);

$db             = new Database();

$request        = new Request();

$cookie         = new CookieCore();

$session        = new SessionCore();

$encrypter      = new Encrypter();

/**
 * Initialized Support Object
 */
$cookieSupport      = new Cookie($request, $cookie);

$sessionSupport     = new Session($session);

$cryptSupport       = new Crypt($encrypter);

$databaseSupport    = new DB($db);

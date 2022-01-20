<?php

global $mulViewDir;

$template_v1    = new TemplateEngine($mulViewDir["resources.views"]);

$db             = new Database();

$request        = new Request();

$cookie         = new CookieCore();

$encrypter      = new Encrypter();

/**
 * Initial Support Object
 */
$cookieSupport      = new Cookie($request, $cookie);

$cryptSupport       = new Crypt($encrypter);

$databaseSupport    = new DB($db);

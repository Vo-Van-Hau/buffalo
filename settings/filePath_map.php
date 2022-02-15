<?php

/**
 * $baseDir: return base folder
 */
$baseDir = dirname(dirname(__FILE__));

return array(

    /**
     * Support file
     */
    /**-------------Static files-------------*/
    "Support\\Str"                              => $baseDir . '/Support/Str.php',
    "Support\\Arr"                              => $baseDir . '/Support/Arr.php',

    /**
     * Core file
     */
    "Kernel\\App"                               => $baseDir . '/Kernel/App.php',
    "Kernel\\Router"                            => $baseDir . '/Kernel/Router.php',
    "Kernel\\TemplateEngine"                    => $baseDir . '/Kernel/TemplateEngine.php',
    "Kernel\\View"                              => $baseDir . '/Kernel/View.php',
    "Kernel\\Database"                          => $baseDir . '/Kernel/Database.php',
    "Kernel\\Request"                           => $baseDir . '/Kernel/Request.php',
    "Kernel\\Response"                          => $baseDir . '/Kernel/Response.php',
    "Kernel\\CookieCore"                        => $baseDir . '/Kernel/CookieCore.php',
    "Kernel\\SessionCore"                       => $baseDir . '/Kernel/SessionCore.php',
    "Kernel\\Encrypter"                         => $baseDir . '/Kernel/Encrypter.php',

    /**
     * Database
     */
    "Kernel\\Database\\PDO\\Connection"                         => $baseDir . '/Kernel/Database/PDO/Connection.php',

    /**
     * Support file
     */
    /**-------------Dynamic files-------------*/
    "Support\\DB"                               => $baseDir . '/Support/DB.php',
    "Support\\Http\\redirect"                   => $baseDir . '/Support/Http/redirect.php',
    "Support\\Http\\request"                    => $baseDir . '/Support/Http/request.php',
    "Support\\Cookie"                           => $baseDir . '/Support/Cookie.php',
    "Support\\Session"                          => $baseDir . '/Support/Session.php',
    "Support\\Crypt"                            => $baseDir . '/Support/Crypt.php',

    /**
     * Build file
     */
    "app\\Http\\Controllers\\Controller"        => $baseDir . '/app/Http/Controllers/Controller.php',
    "app\\Http\\Controllers\\Welcome"           => $baseDir . '/app/Http/Controllers/Welcome.php',
);
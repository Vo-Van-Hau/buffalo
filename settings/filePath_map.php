<?php

/**
 * $baseDir: return base folder
 */
$baseDir = dirname(dirname(__FILE__));

return array(

    /**
     * Core file
     */
    "Kernel\\App"                               => $baseDir . '/Kernel/App.php',
    "Kernel\\Router"                            => $baseDir . '/Kernel/Router.php',
    "Kernel\\TemplateEngine"                    => $baseDir . '/Kernel/TemplateEngine.php',
    "Kernel\\View"                              => $baseDir . '/Kernel/View.php',
    "Kernel\\Database"                          => $baseDir . '/Kernel/Database.php',

    /**
     * Support file
     */
    "Support\\DB"                          => $baseDir . '/Support/DB.php',

    /**
     * Build file
     */
    "app\\Http\\Controllers\\Welcome"             => $baseDir . '/app/Http/Controllers/Welcome.php',
);
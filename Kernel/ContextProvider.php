<?php

global $mulViewDir;

$template_v1    = new TemplateEngine($mulViewDir["resources.views"]);

$db             = new Database();

$request        = new Request();


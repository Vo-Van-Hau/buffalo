<?php   

    $router = new Router();

    $router->get('/', function() {

        return view('layouts/app.view.php');
    });
?>
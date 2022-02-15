<?php 

class App {
    
    function __construct(){

        $arrUrl = $this->URLHandler();

        /**
         *  <-----------------------------------------Url Handler----------------------------------------->
         */
        if(isset($arrUrl) && gettype($arrUrl) === "string"){ 

            echo $arrUrl;
        }
        else {

            require_once(PATH_ROOT . '/routes/web.php');

            $request_url = $_SERVER['REQUEST_URI'];

            $method_url = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

            $router->mapRoute($request_url, $method_url);
        }
    }

    function URLHandler() {

        $urlPage = '';

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){

            $urlPage = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        }else{

            $urlPage = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        }

        return parse_url($urlPage);
    }
}

?>
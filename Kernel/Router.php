<?php 

    class Router {

        private $routes;

        function __construct() {
            
            $this->routes = [];
        }

        public function get(string $url, $action) {

            $this->__request($url, 'GET', $action);
        }

        public function post(string $url, $action) {

            $this->__request($url, 'POST', $action);
        }

        public function __request(string $url, string $method, $action) {
            
            if(preg_match_all('/({([a-zA-Z0-9_]+)})/', $url, $params)) { 

                $url = preg_replace('/({([a-zA-Z0-9_]+)})/', '(.+)', $url); 
            }

            $url = str_replace('/', '\/', $url);

            $route = [
                'url' => $url,
                'method' => $method,
                'action' => $action,
                'params' => $params[2],
            ];

            /**
             * Register for routing
             */
            array_push($this->routes, $route);
        }

        public function mapRoute(string $url, string $method) {

            foreach( $this->routes as $route) {

                if($route['method'] == $method) {

                    $reg = '/^' . $route['url'] . '$/';

                    if(preg_match($reg, $url, $params)) {

                        array_shift($params);

                        $this->__call_action_route($route['action'], $params);

                        return;
                    }
                }
            }

            echo '404 - Not Found';

            return;
        }

        public function __call_action_route($action, $params) {

            /**
             * Checking url has query string or not
             */
            $p_checkParams = "/\?/";

            if(count($params) > 0) {

                if(preg_match($p_checkParams, $params[0], $paramsC) === 1 && count($params) > 0) {

                    $params[0] = explode($paramsC[0], $params[0])[0];
                }
            }

            if(is_callable($action)) {

                call_user_func_array($action, $params);

                return false;
            }

            if(is_string($action)) {

                $action = explode('@', $action);

                require_once('./app/Http/Controllers/' . $action[0] . '.php');

                $Controller_Name = $action[0];

                $Controller = new $Controller_Name();

                call_user_func_array([

                    $Controller, 

                    $action[1]
                    
                ], $params);

                return false;
            }

            if(is_array($action)) {

                $Controller_Name = $action[0];

                $Controller = new $Controller_Name();

                call_user_func_array([

                    $Controller, 

                    $action[1]

                ], $params);

                return false;
            }
        }
    }
?>
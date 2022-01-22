<?php 

    class Router {

        /**
         * The route collection instance.
         */
        protected $routes;

        /**
         * All of the short-hand keys for middlewares.
         *
         * @var array
         */
        protected $middleware = [];

        /**
         * All of the verbs supported by the router.
         *
         * @var string[]
         */
        public static $verbs = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

        function __construct() {
            
            $this->routes = [];
        }

        /**
         * Register a new GET route with the router.
         *
         * @param  string  $uri
         * @param  array|string|callable|null  $action
         */
        public function get(string $uri, $action = null) {

            $this->__request($uri, 'GET', $action);
        }

        /**
         * Register a new POST route with the router.
         *
         * @param  string  $uri
         * @param  array|string|callable|null  $action
         */
        public function post(string $uri, $action) {

            $this->__request($uri, 'POST', $action);
        }

        
        /**
         * --------------------------------------------------------------End Build-Stack--------------------------------------------------------------
         */

        /**
         * Add a Route instance to the collection.
         */
        public function add(Route $route){


        }

        /**
         * Register a new PUT route with the router.
         *
         * @param  string  $uri
         * @param  array|string|callable|null  $action
         */
        public function put($uri, $action = null) {

            return $this->addRoute('PUT', $uri, $action);
        }

        /**
         * Register a new PATCH route with the router.
         *
         * @param  string  $uri
         * @param  array|string|callable|null  $action
         */
        public function patch($uri, $action = null) {

            return $this->addRoute('PATCH', $uri, $action);
        }

        /**
         * Register a new DELETE route with the router.
         *
         * @param  string  $uri
         * @param  array|string|callable|null  $action
         */
        public function delete($uri, $action = null) {

            return $this->addRoute('DELETE', $uri, $action);
        }

        /**
         * Register a new OPTIONS route with the router.
         *
         * @param  string  $uri
         * @param  array|string|callable|null  $action
         * @return \Illuminate\Routing\Route
         */
        public function options($uri, $action = null) {

            return $this->addRoute('OPTIONS', $uri, $action);
        }

        /**
         * Add a route to the underlying route collection.
         *
         * @param  array|string  $methods
         * @param  string  $uri
         * @param  array|string|callable|null  $action
         * @return \Illuminate\Routing\Route
         */
        public function addRoute($methods, $uri, $action) {

            return $this->routes->add($this->createRoute($methods, $uri, $action));
        }

            /**
         * Create a new route instance.
         *
         * @param  array|string  $methods
         * @param  string  $uri
         * @param  mixed  $action
         * @return \Illuminate\Routing\Route
         */
        protected function createRoute($methods, $uri, $action) {

            // If the route is routing to a controller we will parse the route action into
            // an acceptable array format before registering it and creating this route
            // instance itself. We need to build the Closure that will call this out.
            if ($this->actionReferencesController($action)) {
                $action = $this->convertToControllerAction($action);
            }

            $route = $this->newRoute(
                $methods, $this->prefix($uri), $action
            );

            // If we have groups that need to be merged, we will merge them now after this
            // route has already been created and is ready to go. After we're done with
            // the merge we will be ready to return the route back out to the caller.
            if ($this->hasGroupStack()) {
                $this->mergeGroupAttributesIntoRoute($route);
            }

            $this->addWhereClausesToRoute($route);

            return $route;
        }

        /**
         * Create a new Route object.
         *
         * @param  array|string  $methods
         * @param  string  $uri
         * @param  mixed  $action
         * @return \Illuminate\Routing\Route
         */
        public function newRoute($methods, $uri, $action) {

            return (new Route($methods, $uri, $action))->setRouter($this)->setContainer($this->container);
        }


        /**
         * Get or set the middlewares attached to the route.
         *
         * @param  array|string|null  $middleware
         * @return $this|array
         */
        public function middlware($action, $params) {

        }


        /**
         * --------------------------------------------------------------End Build-Stack--------------------------------------------------------------
         */


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

            return false;
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

            /**
             * Old versions
             */
            if(is_string($action)) {

                $action = explode('@', $action);

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

                /**
                 *  [Controller, Method] is equal to Controller::Method, i.e. a static method 
                 * 
                 *  Docs: https://www.php.net/manual/en/language.types.callable.php
                 */
                call_user_func_array([

                    $Controller, 

                    $action[1]

                ], $params);

                return false;
            }

            if(is_callable($action)) {

                call_user_func_array($action, $params);

                return false;
            }
        }
    }
?>
<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => router.php (application routing system)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//url routing and dispatching
class Router {

    private $url = array();
    private $params = array();
    private $routes = array();
    private $methods = array();
    private $controller = 'home'; //default application controller
    private $method = 'index'; //default controller method

    //get uri parameters
    public function __construct() {
        $uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $uri = trim($uri, '/');
        $uri = explode('/', $uri);
        $this->url = array_slice($uri, 1, count($uri));
    }

    //add custom routes for redirection
    public function add_custom_route(string $custom_route, string $route, array $methods) {
        $this->routes[$custom_route] = $route;
        $this->methods[$custom_route] = $methods;
    } 

    public function dispatch() {
        //retrieves controller name as first parameter
        $this->controller = $this->url[0] ?? $this->controller;
        unset($this->url[0]);

        //retrieves method name as second parameter
        $this->method = $this->url[1] ?? $this->method;
        unset($this->url[1]);

        //check custom routes for redirection
        if (!empty($this->routes)) {
            if (array_key_exists($this->controller, $this->routes)) {
                $methods = $this->methods[$this->controller];
                $this->controller = $this->routes[$this->controller];

                if (array_key_exists($this->method, $methods)) {
                    $this->method = $methods[$this->method];
                }
            }
        }
        
        //load controller class
        $controllerClass = load_controller($this->controller);

        //return a 404 error if controller filename not found or method does not exists
        if ($controllerClass === NULL || !method_exists($controllerClass, $this->method)) {
            $error_controller = load_controller('error');
            $error_controller->error_404();
            exit();
        }

        //set parameters
        $params = $this->url ?? array();
        $this->params = isset($_POST) ? array_merge($params, array_values($_POST)) : $params ;
        
        //execute controller with method and parameter
        call_user_func_array([$controllerClass, $this->method], $this->params);
    }
}

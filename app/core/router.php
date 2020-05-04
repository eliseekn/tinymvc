<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Router
 * 
 * Application routing system
 */
class Router
{
    private $url = array();
    private $params = array();
    private $routes = array(); //custom routes
    private $methods = array(); //custom methods
    private $controller = 'home'; //default application controller
    private $method = 'index'; //default controller method

    /**
     * set url parameters form uri
     *
     * @return void
     */
    public function __construct()
    {
        $uri = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $uri = trim($uri, '/');
        $uri = explode('/', $uri);
        $this->url = array_slice($uri, 1, count($uri));
    }

    /**
     * add custom routes for redirection
     *
     * @param  string $custom_route custom route controller name
     * @param  string $route controller name
     * @param  string $methods associated methods to controllers
     * @return void
     */
    public function add_custom_route(string $custom_route, string $route, array $methods): void
    {
        $this->routes[$custom_route] = $route;
        $this->methods[$custom_route] = $methods;
    }

    /**
     * load controller and method with parameters
     *
     * @return void
     */
    public function dispatch(): void
    {
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
        $this->controller = load_controller($this->controller);

        //return a 404 error if controller filename not found or method does not exists
        if ($this->controller === NULL || !method_exists($this->controller, $this->method)) {
            $error_controller = load_controller('error');
            $error_controller->error_404();
            exit();
        }

        //set parameters
        $params = $this->url ?? array();
        $this->params = isset($_POST) ? array_merge($params, array_values($_POST)) : $params;

        //execute controller with method and parameter
        call_user_func_array(
            array(
                $this->controller,
                $this->method
            ),
            $this->params
        );
    }
}

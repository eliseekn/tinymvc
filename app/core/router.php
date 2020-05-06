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
    private $actions = array(); //custom actions
    private $controller = 'home'; //default application controller
    private $action = 'index'; //default controller action

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
     * @param  array $routes custom routes and actons
     * @return void
     */
    public function add_custom_routes(array $routes): void
    {
        if (empty($routes)) {
            return;
        }

        foreach ($routes as $custom_route => $route) {
            $custom_controller = explode('/', $custom_route)[0];
            $custom_action = explode('/', $custom_route)[1];
            $controller = explode('/', $route)[0];
            $action = explode('/', $route)[1];

            $this->routes[$custom_controller] = $controller;
            $this->actions[$custom_controller] = array($custom_action => $action);
        }
    }

    /**
     * load controller and action with parameters
     *
     * @return void
     */
    public function dispatch(): void
    {
        //retrieves controller name as first parameter
        $this->controller = $this->url[0] ?? $this->controller;
        unset($this->url[0]);

        //retrieves action name as second parameter
        $this->action = $this->url[1] ?? $this->action;
        unset($this->url[1]);

        //check custom routes for redirection
        if (!empty($this->routes)) {
            if (array_key_exists($this->controller, $this->routes)) {
                $actions = $this->actions[$this->controller];
                $this->controller = $this->routes[$this->controller];

                if (array_key_exists($this->action, $actions)) {
                    $this->action = $actions[$this->action];
                }
            }
        }

        //load controller class
        $this->controller = load_controller($this->controller);

        //return a 404 error if controller filename not found or action does not exists
        if ($this->controller === NULL || !method_exists($this->controller, $this->action)) {
            $error_controller = load_controller('error');
            $error_controller->error_404();
            exit();
        }

        //set parameters
        $params = $this->url ?? array();
        $this->params = isset($_POST) ? array_merge($params, array_values($_POST)) : $params;

        //execute controller with action and parameter
        call_user_func_array(
            array(
                $this->controller,
                $this->action
            ),
            $this->params
        );
    }
}

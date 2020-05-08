<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Router
 * 
 * Application routing system
 */
class Router
{    
    /**
     * route url
     *
     * @var array
     */
    private $url = array();
        
    /**
     * action params
     *
     * @var array
     */
    private $params = array();
        
    /**
     * custom routes
     *
     * @var array
     */
    private $routes = array();
        
    /**
     * custom actions
     *
     * @var array
     */
    private $actions = array();
        
    /**
     * url controller
     *
     * @var mixed
     */
    private $controller;

    /**
     * url action
     *
     * @var mixed
     */
    private $action;

    /**
     * set url parameters from uri
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
        $this->controller = $this->url[0] ?? DEFAULT_CONTROLLER;
        unset($this->url[0]);

        //retrieves action name as second parameter
        $this->action = $this->url[1] ?? DEFAULT_ACTION;
        unset($this->url[1]);

        //set rest of url as parameters
        $this->params = $this->url ?? array();

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
        if (is_null($this->controller) || !method_exists($this->controller, $this->action)) {
            $error_controller = load_controller('error');
            $error_controller->error_404();
            exit();
        }

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

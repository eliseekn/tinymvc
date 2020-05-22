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

namespace Framework\Http;

use Framework\Core\View;

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
    protected $url = [];

    /**
     * url action
     *
     * @var mixed
     */
    protected $methods = [];
        
    /**
     * custom routes
     *
     * @var array
     */
    protected $routes = [];
        
    /**
     * custom actions
     *
     * @var array
     */
    protected $actions = [];
        
    /**
     * action params
     *
     * @var array
     */
    protected $params = [];
        
    /**
     * url controller
     *
     * @var mixed
     */
    protected $controller;

    /**
     * url action
     *
     * @var mixed
     */
    protected $action;
    
    /**
     * request class instance
     *
     * @var mixed
     */
    protected $request;

    /**
     * set url parameters from uri
     *
     * @return void
     */
    public function __construct()
    {
        $this->request = new Request();
        create_session('current_url', $this->request->getUri());

        $uri = filter_var($this->request->getUri(), FILTER_SANITIZE_URL);
        $uri = explode('/', trim($uri, '/'));
        $root = explode('/', trim(APP_ROOT, '/'));
        
        $this->url = $uri === $root ? [] : array_slice($uri, count($root), count($uri));
    }
    
    /**
     * set routes for redirection
     *
     * @param  array $routes custom routes and actons
     * @return void
     */
    private function setRoutes(): void
    {
        if (empty(Route::$routes)) {
            View::render('error_404');
        }
        
        foreach (Route::$routes as $custom_route => $route) {
            list($method, $custom_controller) = explode('/', $custom_route);
            $custom_action = count(explode('/', $custom_route)) > 2 ? explode('/', $custom_route)[2] : '';
            list($controller, $action) = explode('@', $route);

            if (array_key_exists($custom_controller, $this->routes)) {
                $this->actions[$custom_controller][$custom_action] = $action;
                $this->methods[$custom_controller][] = strtoupper(trim($method));
            } else {
                $this->routes[$custom_controller] = $controller;
                $this->actions[$custom_controller] = [$custom_action => $action];
                $this->methods[$custom_controller] = [strtoupper(trim($method))];
            }
        }
    }

    /**
     * redirect to route
     *
     * @param  string $name route name or Controller@action
     * @param  array $params parameters 
     * @return void
     */
    public static function redirectToRoute(string $name, array $params = []): void
    {
        $params = empty($params) ? '' : implode('/', $params);

        if (array_key_exists($name, Route::$names)) {
            $route = Route::$names[$name];
        }
        
        if (isset($route) && in_array($route, Route::$routes, true)) {
            $url = array_search($route, Route::$routes, true);
            $url = str_replace(['GET /', 'POST /', 'PUT /', 'DELETE /', 'PATCH /', 'OPTIONS /', 'ANY /'], '', $url);
            $url = empty($params) ? $url : '/' . $params;
            redirect_to($url);
        } 

        if (in_array($name, Route::$routes, true)) {
            $url = array_search($name, Route::$routes, true);
            $url = str_replace(['GET /', 'POST /', 'PUT /', 'DELETE /', 'PATCH /', 'OPTIONS /', 'ANY /'], '', $url);
            $url = empty($params) ? $url : '/' . $params;
            redirect_to($url);
        }

        //route not found
        View::render('error_404');
    }
    
    /**
     * load controller and action with parameters
     *
     * @return void
     */
    public function dispatch(): void
    {
        //set routes
        $this->setRoutes();

        //retrieves controller name as first parameter
        $this->controller = $this->url[0] ?? '';
        unset($this->url[0]);

        //retrieves action name as second parameter
        $this->action = $this->url[1] ?? '';
        unset($this->url[1]);

        //set rest of url as parameters
        $this->params = $this->url ?? [];

        //check routes for redirection
        if (array_key_exists($this->controller, $this->routes)) {
            if ($this->methods[$this->controller][0] !== 'ANY') {
                if ($this->request->getMethod() !== $this->methods[$this->controller][0]) {
                    View::render('error_404');
                }
            }

            $actions = $this->actions[$this->controller];
            $this->controller = $this->routes[$this->controller];

            if (array_key_exists($this->action, $actions)) {
                $this->action = $actions[$this->action];
            }
        }

        //load controller class
        $this->controller = 'App\Controllers\\' . $this->controller;

        //return a 404 error if controller filename not found or action does not exists
        if (!class_exists($this->controller) || !method_exists($this->controller, $this->action)) {
            View::render('error_404');
        }

        //check for middlewares to execute
        Middleware::check($this->controller . '@' . $this->action);

        //execute controller with action and parameter
        call_user_func_array([new $this->controller(), $this->action], $this->params);
    }
}

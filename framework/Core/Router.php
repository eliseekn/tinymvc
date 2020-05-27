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

namespace Framework\Core;

use Exception;
use Framework\Core\View;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Exceptions\RoutesNotDefineException;
use Framework\Exceptions\ControllerNotFoundException;

/**
 * Router
 * 
 * Routing system
 */
class Router
{
    /**
     * route uri
     *
     * @var string
     */
    protected $uri = '';

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
        $this->uri = $this->request->getURI();
        $this->addSessionHistory();
        
        try {
            $this->dispatch(Route::$routes);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
    
    /**
     * add uri to browsing history session
     *
     * @return void
     */
    private function addSessionHistory(): void
    {
        $browsing_history = get_session('browsing_history');

        if (empty($browsing_history)) {
            $browsing_history = [$this->uri];
        } else {
            $browsing_history[] = $this->uri;
        }

        create_session('browsing_history', $browsing_history);
    }
    
    /**
     * match routes and execute controllers
     *
     * @param  array $routes routes
     * @return void
     */
    private function dispatch(array $routes): void
    {
        if (!empty($routes)) {
            foreach ($routes as $route => $data) {
                $route = preg_replace('/{([a-z]+):([^\}]+)}/i', '$2', $route);
                $route = preg_replace(['/\bstr\b/', '/\bint\b/', '/\ball\b/'], ['([a-zA-Z0-9-]+)', '(\d+)', '([^/]+)'], $route);
                $pattern = '#^' . $route . '$#';

                if (preg_match($pattern, $this->uri, $params)) {
                    array_shift($params);

                    if (preg_match('/' . strtoupper($data['method']) . '/', $this->request->getMethod())) {
                        list($controller, $action) = explode('@', $data['controller']);
                        $controller = 'App\Controllers\\' . $controller;

                        //chekc if controller class and method exist
                        if (class_exists($controller) && method_exists($controller, $action)) {
                            //check for middlewares to execute
                            Middleware::check($data['controller']);

                            //execute controller with action and parameter
                            call_user_func_array([new $controller(), $action], array_values($params));
                        } else {
                            throw new ControllerNotFoundException($data['controller']);
                        }
                    }
                }
            }

            //send 404 response
            if (isset(ERRORS_PAGE['404']) && !empty(ERRORS_PAGE['404'])) {
                View::error(ERRORS_PAGE['404']);
            } else {
                Response::send([], 'The page you have requested does not exists on this server.', 404);
            }
        } else {
            throw new RoutesNotDefineException();
        }
    }
}

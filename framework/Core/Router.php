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

use Framework\Http\Request;

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
        $this->parseURI();
        $this->addSessionHistory();
        $this->dispatch(Route::$routes);
    }
    
    /**
     * parse requested uri
     *
     * @return void
     */
    private function parseURI(): void
    {
        $this->uri = filter_var($this->request->getURI(), FILTER_SANITIZE_URL);
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
                            View::render('404');
                        }
                    }
                }
            }

            View::render('404');
        } else {
            View::render('404');
        }
    }
}

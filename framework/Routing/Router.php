<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Routing;

use Exception;
use Framework\HTTP\Request;
use Framework\Routing\View;
use Framework\HTTP\Response;
use Framework\Support\Session;

/**
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
     * set url parameters from uri
     *
     * @return void
     */
    public function __construct()
    {
        //set history
        Session::history();
        
        //dispatch routes
        try {
            $this->dispatch(Route::$routes);
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
    
    /**
     * format uri to add '/' before or remove '/' at the end
     *
     * @param  string $uri
     * @return string
     */
    private function formatUri(string $uri): string
    {
        if (strlen($uri) > 1) {
            if ($uri[strlen($uri) - 1] === '/') {
                $uri = rtrim($uri, '/');
            }
        }

        return $uri;
    }
    
    /**
     * match routes and execute handlers
     *
     * @param  array $routes routes
     * @return void
     */
    private function dispatch(array $routes): void
    {
        $request = new Request();

        if (!empty($routes)) {
            foreach ($routes as $route => $options) {
                if (preg_match('/' . strtoupper($options['method']) . '/', $request->getMethod())) {
                    $pattern = preg_replace('/{([a-zA-Z-_]+):([^\}]+)}/i', '$2', $route);
                    $pattern = preg_replace('/{([a-zA-Z-_]+):([^\}]+)}?/i', '$2', $pattern);
                    $pattern = preg_replace('/\bstr\b/', '([a-zA-Z-_]+)', $pattern);
                    $pattern = preg_replace('/\bnum\b/', '(\d+)', $pattern);
                    $pattern = preg_replace('/\bany\b/', '([^/]+)', $pattern);
                    $pattern = '#^' . $pattern . '$#';

                    if (preg_match($pattern, $this->formatUri($request->getUri()), $params)) {
                        array_shift($params);

                        if (is_callable($options['handler'])) {
                            //check for middlewares to execute
                            Middleware::check($route);

                            //execute function
                            call_user_func_array($options['handler'], array_values($params));
                        } else {
                            list($controller, $action) = explode('@', $options['handler']);
                            $controller = 'App\Controllers\\' . $controller;

                            //chekc if controller class and method exist
                            if (class_exists($controller) && method_exists($controller, $action)) {
                                //check for middlewares to execute
                                Middleware::check($route);

                                //execute controller with action and parameter
                                call_user_func_array([new $controller($request), $action], array_values($params));
                            } else {
                                throw new Exception('Handler "' . $options['handler'] . '" not found.');
                            }
                        }
                    }
                }
            }

            //send 404 response
            if (!empty(config('errors.views.404'))) {
                View::render(config('errors.views.404'), [], 404);
            } else {
                Response::send('The page you have requested does not exists', [], 404);
            }
        } else {
            throw new Exception('No route defines in configuration');
        }
    }
}

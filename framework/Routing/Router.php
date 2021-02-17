<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Exception;
use Framework\Http\Request;
use Framework\Routing\View;
use Framework\Http\Response;
use Framework\Support\Session;

/**
 * Routing system
 */
class Router
{
    /**
     * set sesssion history 
     *
     * @return void
     */
    public static function  history(Request $request): void
    {
        $url = Session::get('history');

        //excludes api uri from browsing history
        if (!in_url('api')) {
            if (empty($url)) {
                $url = [$request->uri(true)];
            } else {
                $url[] = $request->uri(true);
            }
        }

        Session::create('history', $url);
    }
    
    /**
     * match routes and execute handlers
     *
     * @param  array $routes routes
     * @return void
     */
    public static function dispatch(Request $request, array $routes): void
    {
        if (!empty($routes)) {
            foreach ($routes as $route => $options) {
                $options['method'] = strtoupper($options['method']);
                $request->setMethod($request->get('request_method', $options['method']));

                if (preg_match('/' . strtoupper($options['method']) . '/', $request->method())) {
                    $pattern = preg_replace('/{str}/i', '([a-zA-Z-_]+)', $route);
                    $pattern = preg_replace('/{num}/i', '(\d+)', $pattern);
                    $pattern = preg_replace('/{any}/i', '([^/]+)', $pattern);

                    if (preg_match('#^' . $pattern . '$#', $request->uri(), $params)) {
                        array_shift($params);

                        //check for middlewares to execute
                        Middleware::check($route, $request);

                        if (is_callable($options['handler'])) {
                            //execute function with parameters
                            call_user_func_array($options['handler'], array_values($params));
                        } else {
                            list($controller, $action) = explode('@', $options['handler']);
                            $controller = 'App\Controllers\\' . $controller;

                            //chekc if controller class and method exist
                            if (class_exists($controller) && method_exists($controller, $action)) {
                                //execute controller with method and parameters
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
                Response::send('The page you have requested does not exists', false, [], 404);
            }
        } else {
            throw new Exception('No route defines in configuration');
        }
    }
}

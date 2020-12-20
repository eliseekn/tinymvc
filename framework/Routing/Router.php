<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
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
        $this->setHistory();
        
        //dispatch routes
        try {
            $this->dispatch(Route::$routes);
        } catch (Exception $e) {
            //log exception message
            if (config('errors.log') === true) {
                save_log('Application Error: ' . $e->getMessage());
            }

            //send 500 response
            if (config('errors.display') === true) {
                die($e->getMessage());
            } else {
                if (!empty(config('errors.views.500'))) {
                    View::render(config('errors.views.500'), [], 500);
                } else {
                    Response::send('Try to refresh the page or feel free to contact us if the problem persists', [], 500);
                }
            }
        }
    }
    
    /**
     * set history sesssion
     *
     * @return void
     */
    private function setHistory(): void
    {
        $url = Session::get('history');

        //excludes api uri from browser history
        if (!in_url('api')) {
            if (empty($url)) {
                $url = [Request::getFullUri()];
            } else {
                $url[] = Request::getFullUri();
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
    private function dispatch(array $routes): void
    {
        $request = new Request();

        if (!empty($routes)) {
            foreach ($routes as $route => $options) {
                if (preg_match('/' . strtoupper($options['method']) . '/', $request->method())) {
                    $pattern = preg_replace('/{str}/i', '([a-zA-Z-_]+)', $route);
                    $pattern = preg_replace('/{num}/i', '(\d+)', $pattern);
                    $pattern = preg_replace('/{any}/i', '([^/]+)', $pattern);

                    if (preg_match('#^' . $pattern . '$#', $request->uri(), $params)) {
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

<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Closure;
use Exception;
use Framework\System\Auth;
use Framework\Http\Request;
use Framework\System\Session;
use Framework\System\DependcyInjection;

/**
 * Routing system
 */
class Router
{
    /**
     * check if request uri match route
     *
     * @param  \Framework\Http\Request $request
     * @param  string $method
     * @param  string $route
     * @param  string[] &$matches
     * @return bool
     */
    private static function match(Request $request, string $method, string $route, &$matches): bool
    {
        if (preg_match('/' . strtoupper($method) . '/', strtoupper($request->method()))) {
            if (preg_match('#^' . $route . '$#', $request->uri(), $matches)) {
                array_shift($matches);
                return true;
            }
        }

        return false;
    }
    
    /**
     * check for locked routes
     *
     * @param  array $roles
     * @return void
     */
    private static function checkLocked(array $roles): void
    {
        if (in_array(Auth::get('role'), $roles)) {
            if (!empty(config('errors.views.403'))) {
                render(config('errors.views.403'), [], 403);
            }
                
            response()->send(__('access_denied'), [], 403);
        }
    }
    
    /**
     * match routes and execute handlers
     *
     * @param  \Framework\Http\Request $request
     * @param  array $routes
     * @return void
     * 
     * @throws Exception
     */
    public static function dispatch(Request $request, array $routes): void
    {   
        if (empty($routes)) {
            throw new Exception('No route defined');
        }

        foreach ($routes as $route => $options) {
            $request->method($request->inputs('request_method', $options['method']));

            if (self::match($request, $options['method'], $route, $params)) {
                //check access to protected routes
                if (isset($options['locked'])) {
                    self::checkLocked($options['locked']);
                }

                //add route to browsing history and exclude API
                if (!in_url('api')) {
                    Session::put('history', [$request->fullUri()]);
                }

                //execute routes middlewares if set
                if (isset($options['middlewares'])) {
                    Middleware::check($route);
                }

                //handler is closure
                if ($options['handler'] instanceof Closure) {
                    call_user_func_array($options['handler'], array_values($params));
                } 
                
                //handler is view template
                else if (is_string($options['handler'])) {
                    render($options['handler']);
                }
                
                //handler is controller and action
                else if (is_array($options['handler'])) {
                    list($controller, $action) = $options['handler'];

                    if (class_exists($controller) && method_exists($controller, $action)) {
                        (new DependcyInjection())->resolve($controller, $action, $params);
                    }
                        
                    throw new Exception('Controller "' . $controller . '/' . $action . '" not found.');
                }
            }
        }

        //send 404 response
        if (!empty(config('errors.views.404'))) {
            render(config('errors.views.404'), [], 404);
        }
            
        response()->send(__('page_not_exists'), [], 404);
    }
}

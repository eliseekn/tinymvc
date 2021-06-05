<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

use Closure;
use Exception;
use Core\System\Auth;
use Core\Http\Request;
use Core\System\Session;
use Core\System\DependcyInjection;

/**
 * Routing system
 */
class Router
{
    /**
     * check if request uri match route
     *
     * @param  \Core\Http\Request $request
     * @param  string $method
     * @param  string $route
     * @param  string[] &$params
     * @return bool
     */
    private static function match(Request $request, string $method, string $route, &$params): bool
    {
        if (preg_match('/' . strtoupper($method) . '/', strtoupper($request->method())) === false) {
            render(config('errors.views.405'), [], 405);
        }

        if (preg_match('#^' . $route . '$#', $request->uri(), $params)) {
            array_shift($params);
            return true;
        }

        return false;
    }
    
    /**
     * check if route is locked
     *
     * @param  array $roles
     * @return void
     */
    private static function isLocked(array $roles): void
    {
        if (in_array(Auth::get('role'), $roles)) {
            render(config('errors.views.403'), [], 403);
        }
    }
    
    /**
     * execute route middlewares
     *
     * @param  array $middlewares
     * @return void
     * 
     * @throws Exception
     */
    private static function executeMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            $middleware = config('middlewares.' . $middleware);

            if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
                throw new Exception('Middleware "' . $middleware . '" not found');
            }

            (new DependcyInjection())->resolve($middleware, 'handle');
        }
    }
    
    /**
     * load route handler
     *
     * @param  mixed $handler
     * @param  array $params
     * @return void
     * 
     * @throws Exception
     */
    private static function loadHandler($handler, array $params): void
    {
        //handler is closure
        if ($handler instanceof Closure) {
            call_user_func_array($handler, array_values($params));
        } 
        
        //handler is view template
        else if (is_string($handler)) {
            render($handler);
        }
        
        //handler is controller and action
        else if (is_array($handler)) {
            list($controller, $action) = $handler;

            if (class_exists($controller) && method_exists($controller, $action)) {
                (new DependcyInjection())->resolve($controller, $action, $params);
            }
                
            throw new Exception('Controller "' . $controller . '/' . $action . '" not found.');
        }
    }
    
    /**
     * match routes and execute handlers
     *
     * @param  \Core\Http\Request $request
     * @return void
     */
    public static function dispatch(Request $request): void
    {   
        $routes = Route::$routes;

        if (empty($routes)) {
            throw new Exception('No route defined');
        }

        foreach ($routes as $route => $options) {
            list($method, $route) = explode(' ', $route, 2);

            $request_method = $request->inputs('_method', $request->method());
            $request->method($request_method);

            if (self::match($request, $method, $route, $params)) {
                if (!isset($options['handler'])) {
                    throw new Exception('No handler defined for route "' . $route . '".');
                }

                //check if route is locked
                if (isset($options['locked'])) {
                    self::isLocked($options['locked']);
                }

                //add route to browsing history
                if (!url_contains('api')) {
                    Session::put('history', [$request->fullUri()]);
                }

                //execute middlewares
                if (isset($options['middlewares'])) {
                    self::executeMiddlewares($options['middlewares']);
                }

                //load handler
                self::loadHandler($options['handler'], $params);
            }
        }

        //send 404 response
        render(config('errors.views.404'), [], 404);
    }
}

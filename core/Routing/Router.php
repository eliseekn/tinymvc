<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

use Closure;
use Exception;
use Core\Support\Auth;
use Core\Http\Request;
use Core\Support\Session;
use Core\Support\DependencyInjection;

/**
 * Routing system
 */
class Router
{
    private static function match(Request $request, string $method, string $route, &$params)
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
    
    private static function isLocked(array $roles)
    {
        if (in_array(Auth::get('role'), $roles)) {
            render(config('errors.views.403'), [], 403);
        }
    }
    
    private static function executeMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $middleware = config('middlewares.' . $middleware);

            if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
                throw new Exception('Middleware "' . $middleware . '" not found');
            }

            (new DependencyInjection())->resolve($middleware, 'handle');
        }
    }
    
    /**
     * @throws Exception
     */
    private static function executeHandler($handler, array $params)
    {
        //handler is closure
        if ($handler instanceof Closure) {
            call_user_func_array($handler, array_values($params));
        } 
        
        //handler is string, means is view template
        elseif (is_string($handler)) {
            render($handler);
        }
        
        //handler is controller and action
        elseif (is_array($handler)) {
            list($controller, $action) = $handler;

            if (class_exists($controller) && method_exists($controller, $action)) {
                (new DependencyInjection())->resolve($controller, $action, $params);
            }
                
            throw new Exception('Controller "' . $controller . '/' . $action . '" not found.');
        }
    }
    
    /**
     * @throws Exception
     */
    public static function dispatch(Request $request)
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

                //add route to browsing history if api not in uri
                if (!$request->uriContains('api')) {
                    Session::put('history', [$request->fullUri()]);
                }

                //execute middlewares
                if (isset($options['middlewares'])) {
                    self::executeMiddlewares($options['middlewares']);
                }

                //execute handler
                self::executeHandler($options['handler'], $params);
            }
        }

        //send 404 response
        render(config('errors.views.404'), [], 404);
    }
}

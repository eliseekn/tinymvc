<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

use Closure;
use Exception;
use Core\Http\Request;
use Core\Http\Response\Response;
use Core\Support\Session;
use Core\Support\DependencyInjection;

/**
 * Routing system
 */
class Router
{
    private static function match(Request $request, string $method, string $route, &$params)
    {
        if (
            !preg_match('/' . strtoupper($method) . '/', strtoupper($request->method())) ||
            !preg_match('#^' . $route . '$#', $request->uri(), $params)
        ) {
            return false;
        }

        array_shift($params);
            
        return true;
    }
    
    /**
     * @throws Exception
     */
    private static function executeMiddlewares(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $middleware = config('middlewares.' . $middleware);

            if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
                throw new Exception("Middleware $middleware not found");
            }

            (new DependencyInjection())->resolve($middleware, 'handle');
        }
    }
    
    /**
     * @throws Exception
     */
    private static function executeHandler($handler, array $params)
    {
        if ($handler instanceof Closure) {
            (new DependencyInjection())->resolveClosure($handler, $params);
        } 
        
        if (is_array($handler)) {
            list($controller, $action) = $handler;

            if (class_exists($controller) && method_exists($controller, $action)) {
                (new DependencyInjection())->resolve($controller, $action, $params);
            }
            
            throw new Exception("Controller $controller/$action not found");
        }

        if (is_string($handler)) {
            if (class_exists($handler)) {
                (new DependencyInjection())->resolve($handler, '__invoke', $params);
            }

            throw new Exception("Controller $handler not found");
        }

        throw new Exception('Invalid route handler');
    }
    
    /**
     * @throws Exception
     */
    public static function dispatch(Request $request, Response $response)
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
                    throw new Exception("No handler defined for route {$route}");
                }

                if (!$request->uriContains('api')) {
                    Session::push('history', [$request->uri()]);
                }

                if (isset($options['middlewares'])) {
                    self::executeMiddlewares($options['middlewares']);
                }

                self::executeHandler($options['handler'], $params);
            }
        }

        $response->view(config('errors.views.404'), [], [], 404);
    }
}

<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

use Closure;
use Core\Http\Request;
use Core\Support\Session;
use Core\Http\Response;
use Core\Support\DependencyInjection;
use Core\Exceptions\RoutesNotDefinedException;
use Core\Exceptions\ControllerNotFoundException;
use Core\Exceptions\MiddlewareNotFoundException;
use Core\Exceptions\InvalidRouteHandlerException;
use Core\Exceptions\RouteHandlerNotDefinedException;

/**
 * Routing system
 */
class Router
{
    private static function match(Request $request, string $method, string $route, &$params): bool
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
    
    private static function executeMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            $middleware = config('middlewares.' . $middleware);

            if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
                throw new MiddlewareNotFoundException($middleware);
            }

            (new DependencyInjection())->resolve($middleware, 'handle');
        }
    }
    
    private static function executeHandler($handler, array $params): mixed
    {
        if ($handler instanceof Closure) {
            return (new DependencyInjection())->resolveClosure($handler, $params);
        } 
        
        if (is_array($handler)) {
            list($controller, $action) = $handler;

            if (class_exists($controller) && method_exists($controller, $action)) {
                return (new DependencyInjection())->resolve($controller, $action, $params);
            }
            
            throw new ControllerNotFoundException("$controller/$action");
        }

        if (is_string($handler)) {
            if (class_exists($handler)) {
                return (new DependencyInjection())->resolve($handler, '__invoke', $params);
            }

            throw new ControllerNotFoundException($handler);
        }

        throw new InvalidRouteHandlerException();
    }
    
    public static function dispatch(Request $request, Response $response): void
    {   
        $routes = Route::$routes;

        if (empty($routes)) {
            throw new RoutesNotDefinedException();
        }

        foreach ($routes as $route => $options) {
            list($method, $route) = explode(' ', $route, 2);

            $request_method = $request->inputs('_method', $request->method());
            $request->method($request_method);

            if (self::match($request, $method, $route, $params)) {
                if (!isset($options['handler'])) {
                    throw new RouteHandlerNotDefinedException($route);
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

        $response->view(view: config('errors.views.404'))->send(404);
    }
}

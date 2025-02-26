<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Routing;

use Closure;
use Core\Enums\HttpMethod;
use Core\Exceptions\ControllerNotFoundException;
use Core\Exceptions\InvalidRouteHandlerException;
use Core\Exceptions\MiddlewareNotFoundException;
use Core\Exceptions\RouteHandlerNotDefinedException;
use Core\Exceptions\RoutesNotDefinedException;
use Core\Http\Request;
use Core\Http\Response;
use Core\Support\DependencyInjection;

/**
 * Routing system.
 */
class Router
{
    protected static function match(Request $request, string $method, string $route, &$params): bool
    {
        if (
            ! preg_match('/' . strtoupper($method) . '/', strtoupper($request->method())) ||
            ! preg_match('#^' . $route . '$#', $request->uri(), $params)
        ) {
            return false;
        }

        array_shift($params);

        return true;
    }

    protected static function executeMiddlewares(Request $request, array $middlewares): void
    {
        if (in_array(strtoupper($request->method()), [HttpMethod::POST, HttpMethod::PUT, HttpMethod::PUT])) {
            $middlewares = array_merge($middlewares, ['csrf']);
        }

        foreach ($middlewares as $middleware) {
            $middleware = config('middlewares.' . $middleware);

            if (! class_exists($middleware) || ! method_exists($middleware, 'handle')) {
                throw new MiddlewareNotFoundException($middleware);
            }

            (new DependencyInjection)->resolve($middleware, 'handle');
        }
    }

    protected static function executeHandler(Closure|array|string $handler, array $params): mixed
    {
        if ($handler instanceof Closure) {
            return (new DependencyInjection)->resolveClosure($handler, $params);
        }

        if (is_array($handler)) {
            list($controller, $action) = $handler;

            if (class_exists($controller) && method_exists($controller, $action)) {
                return (new DependencyInjection)->resolve($controller, $action, $params);
            }

            throw new ControllerNotFoundException("$controller/$action");
        }

        if (is_string($handler)) {
            if (class_exists($handler)) {
                return (new DependencyInjection)->resolve($handler, '__invoke', $params);
            }

            throw new ControllerNotFoundException($handler);
        }

        throw new InvalidRouteHandlerException();
    }

    public static function dispatch(): void
    {
        $request = new Request();
        $response = new Response();
        $routes = Route::getAll();

        if (empty($routes)) {
            throw new RoutesNotDefinedException();
        }

        foreach ($routes as $route => $options) {
            list($method, $route) = explode(' ', $route, 2);
            $request_method = $request->inputs('_method', $request->method());
            $request->method($request_method);

            if (self::match($request, $method, $route, $params)) {
                if (! isset($options['handler'])) {
                    throw new RouteHandlerNotDefinedException($route);
                }

                if (! $request->uriContains('api')) {
                    session()->push('history', [$request->uri()]);
                }

                if (isset($options['middlewares'])) {
                    self::executeMiddlewares($request, $options['middlewares']);
                }

                self::executeHandler($options['handler'], $params);
            }
        }

        $response->view(config('errors.views.404'))->send(404);
    }
}

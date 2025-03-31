<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
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
use Core\Exceptions\RoutesPathsNotDefinedException;
use Core\Http\Middlewares\CsrfProtection;
use Core\Http\Request;
use Core\Http\Response;
use Core\Support\DependencyInjection;
use Exception;

/**
 * Routing system.
 */
class Router
{
    protected static function match(Request $request, string $route, &$params, array $routeParams = []): bool
    {
        $route = preg_replace_callback('/\{([a-zA-Z0-9_-]+)\??\}/', function ($matches) use ($routeParams) {
            $param = $matches[1];
            $optional = str_contains($matches[0], '?');

            if (! isset($routeParams[$param])) {
                throw new Exception("No pattern defined for parameter: $param");
            }

            $pattern = $routeParams[$param];

            return $optional ? "?$pattern?" : $pattern;
        }, $route);

        if (preg_match('#^'.$route.'$#', $request->method().' '.$request->uri(), $params)) {
            array_shift($params);
            return true;
        }

        return false;
    }

    /**
     * @throws MiddlewareNotFoundException
     */
    protected static function executeMiddlewares(Request $request, array $middlewares): void
    {
        if (in_array(strtoupper($request->method()), [HttpMethod::POST, HttpMethod::PATCH, HttpMethod::PUT])) {
            if (! in_array($request->uri(), config('security.csrf_excluded_uri'))) {
                (new DependencyInjection)->resolve(CsrfProtection::class, 'handle');
            }
        }

        foreach ($middlewares as $middleware) {
            $middleware = config('middlewares.'.$middleware);

            if (! class_exists($middleware) && ! method_exists($middleware, 'handle')) {
                throw new MiddlewareNotFoundException($middleware);
            }

            (new DependencyInjection)->resolve($middleware, 'handle');
        }
    }

    /**
     * @throws InvalidRouteHandlerException
     * @throws ControllerNotFoundException
     */
    protected static function executeHandler(Closure|array|string $handler, array $params, array $bindings): void
    {
        if ($handler instanceof Closure) {
            (new DependencyInjection)->resolveClosure($handler, $params, $bindings);

            return;
        }

        if (is_array($handler)) {
            list($controller, $action) = $handler;

            if (class_exists($controller) && method_exists($controller, $action)) {
                (new DependencyInjection)->resolve($controller, $action, $params, $bindings);

                return;
            }

            throw new ControllerNotFoundException("$controller@$action");
        }

        if (is_string($handler)) {
            if (class_exists($handler)) {
                (new DependencyInjection)->resolve($handler, '__invoke', $params, $bindings);

                return;
            }

            throw new ControllerNotFoundException($handler);
        }

        // @phpstan-ignore-next-line
        throw new InvalidRouteHandlerException;
    }

    /**
     * @throws MiddlewareNotFoundException
     * @throws InvalidRouteHandlerException
     * @throws RoutesNotDefinedException
     * @throws RoutesPathsNotDefinedException
     * @throws RouteHandlerNotDefinedException
     * @throws ControllerNotFoundException
     * @throws Exception
     */
    public static function dispatch(): void
    {
        $request = new Request;
        $response = new Response;
        $routes = Route::getAll();

        if (empty($routes)) {
            throw new RoutesNotDefinedException;
        }

        foreach ($routes as $route => $options) {
            $request_method = $request->inputs('_method', $request->method());
            $request->method($request_method);

            if (self::match($request, $route, $params, $options['parameters'] ?? [])) {
                if (! isset($options['handler'])) {
                    throw new RouteHandlerNotDefinedException($route);
                }

                if (! $request->uriContains('api')) {
                    session()->push('history', [$request->uri()]);
                }

                if (isset($options['middlewares'])) {
                    self::executeMiddlewares($request, $options['middlewares']);
                }

                $bindings = resolve_route_binding($route, $options['parameters'] ?? [], $options['bindings'] ?? []);

                self::executeHandler($options['handler'], $params, $bindings);
            }
        }

        $response->view(config('errors.views.404'))->send();
    }
}

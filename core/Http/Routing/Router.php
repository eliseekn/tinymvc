<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Routing;

use Closure;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Enums\ResponseStatus;
use Core\Exceptions\CoreException;
use Core\Http\Middlewares\CsrfProtection;
use Core\Http\Response\BaseResponse;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\ViewResponse;
use Core\Support\DependencyInjection;

/**
 * Routing system.
 */
class Router
{
    protected static function match(string $route, array &$params, array $routeParams = []): bool
    {
        $route = preg_replace_callback('/\{([a-zA-Z0-9_-]+)\??\}/', function ($matches) use ($routeParams) {
            $param = $matches[1];
            $optional = str_contains($matches[0], '?');

            if (! isset($routeParams[$param])) {
                throw new CoreException("No pattern defined for parameter: $param");
            }

            $pattern = $routeParams[$param];

            return $optional ? "?$pattern?" : $pattern;
        }, $route);

        $route = preg_replace('/^([A-Z|]+) /', '(?:$1) ', $route);

        if (preg_match('#^'.$route.'$#', request()->method().' '.request()->uri(), $params)) {
            array_unshift($params);

            return true;
        }

        return false;
    }

    protected static function executeMiddlewares(array $middlewares): void
    {
        if (in_array(strtoupper(request()->method()), [HttpMethod::POST, HttpMethod::PATCH, HttpMethod::PUT])) {
            if (! request()->isJson() && ! in_array(request()->uri(), config('security.csrf_excluded_uri'))) {
                (new DependencyInjection)->resolve(CsrfProtection::class, 'handle');
            }
        }

        foreach ($middlewares as $middleware) {
            if (! class_exists($middleware) || ! method_exists($middleware, 'handle')) {
                throw new CoreException("Middleware $middleware not found");
            }

            (new DependencyInjection)->resolve($middleware, 'handle');
        }
    }

    protected static function executeHandler(Closure|array|string $handler, array $params, array $bindings): mixed
    {
        if ($handler instanceof Closure) {
            return (new DependencyInjection)->resolveClosure($handler, $params, $bindings);
        }

        if (is_array($handler)) {
            [$controller, $action] = $handler;

            if (class_exists($controller) && method_exists($controller, $action)) {
                return (new DependencyInjection)->resolve($controller, $action, $params, $bindings);
            }

            throw new CoreException("Controller $controller@$action not found");
        }

        if (is_string($handler)) {
            if (class_exists($handler)) {
                return (new DependencyInjection)->resolve($handler, '__invoke', $params, $bindings);
            }

            throw new CoreException("Controller $handler not found");
        }

        // @phpstan-ignore-next-line
        throw new CoreException('Invalid route handler');
    }

    public static function dispatch(): BaseResponse
    {
        $routes = Route::getAll();

        if (empty($routes)) {
            throw new CoreException('No routes defined in ./routes');
        }

        foreach ($routes as $route => $options) {
            $request_method = request()->inputs()->get('_method', request()->method());
            request()->method($request_method);
            $params = [];

            if (self::match($route, $params, $options['parameters'] ?? [])) {
                if (! isset($options['handler'])) {
                    throw new CoreException("No handler defined for route '$route'");
                }

                if (! request()->uriContains('api')) {
                    session()->push('history', [request()->uri()]);
                }

                if (isset($options['middlewares'])) {
                    self::executeMiddlewares($options['middlewares']);
                }

                $bindings = resolve_route_binding($route, $options['parameters'] ?? [], $options['bindings'] ?? []);

                return self::executeHandler($options['handler'], $params, $bindings);
            }
        }

        if (request()->isJson()) {
            return new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'Not found',
            ], HttpCode::NOT_FOUND);
        }

        return new ViewResponse(config('errors.views.404'), []);
    }
}

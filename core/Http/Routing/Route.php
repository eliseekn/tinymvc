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
use Core\Enums\RouteParameter;
use Core\Exceptions\RoutesPathsNotDefinedException;
use Core\Http\Response;
use Core\Http\Routing\Attributes\Route as RouteAttribute;
use ReflectionClass;
use ReflectionMethod;
use Spatie\StructureDiscoverer\Discover;

/**
 * Manage routes.
 */
class Route
{
    protected static string $route;

    protected static array $tmp_routes = [];

    public static array $routes = [];

    public function __construct()
    {
    }

    private static function add(string $route, Closure|array|string $handler): self
    {
        static::$route = $route;
        static::$tmp_routes[static::$route] = ['handler' => $handler];

        return new self();
    }

    public static function get(string $uri, Closure|array|string $handler): self
    {
        return self::add(HttpMethod::GET . ' ' . $uri, $handler);
    }

    public static function post(string $uri, Closure|array|string $handler): self
    {
        return self::add(HttpMethod::POST . ' ' . $uri, $handler);
    }

    public static function delete(string $uri, Closure|array|string $handler): self
    {
        return self::add(HttpMethod::DELETE . ' ' . $uri, $handler);
    }

    public static function options(string $uri, Closure|array|string $handler): self
    {
        return self::add(HttpMethod::OPTIONS . ' ' . $uri, $handler);
    }

    public static function patch(string $uri, Closure|array|string $handler): self
    {
        return self::add(HttpMethod::PATCH . ' ' . $uri, $handler);
    }

    public static function put(string $uri, Closure|array|string $handler): self
    {
        return self::add(HttpMethod::PUT . ' ' . $uri, $handler);
    }

    public static function any(string $uri, Closure|array|string $handler): self
    {
        return self::add(HttpMethod::ANY . ' ' . $uri, $handler);
    }

    public static function all(string $name, string $controller, array $excepts = []): self
    {
        return self::group(function () use ($name, $excepts) {
            if (! in_array('index', $excepts)) {
                self::get('/' . $name, 'index')->name('index');
            }
            if (! in_array('create', $excepts)) {
                self::get('/' . $name, 'create')->name('create');
            }
            if (! in_array('store', $excepts)) {
                self::post('/' . $name, 'store')->name('store');
            }
            if (! in_array('update', $excepts)) {
                self::match('PATCH|PUT', '/' . $name . '/{id:num}', 'update')->name('update');
            }
            if (! in_array('show', $excepts)) {
                self::get('/' . $name . '/{id:num}', 'show')->name('show');
            }
            if (! in_array('edit', $excepts)) {
                self::get('/' . $name . '/{id:num}/edit', 'edit')->name('edit');
            }
            if (! in_array('delete', $excepts)) {
                self::delete('/' . $name . '/{id:num}', 'delete')->name('delete');
            }
        })->byController($controller)->byName($name);
    }

    public static function match(string $methods, string $uri, Closure|array|string $handler): self
    {
        return self::add($methods . ' ' . $uri, $handler);
    }

    public static function view(string $uri, string $view, array $params = []): self
    {
        return self::get($uri, function (Response $response) use ($view, $params) {
            $response->view($view, $params)->send();
        });
    }

    public function name(string $name): self
    {
        self::$tmp_routes[self::$route]['name'] = $name;

        return $this;
    }

    public static function group($callback): self
    {
        call_user_func($callback);

        // @phpstan-ignore-next-line
        return new static();
    }

    public function middleware(array|string $middlewares): self
    {
        $middlewares = parse_array($middlewares);
        self::$tmp_routes[self::$route]['middlewares'] = $middlewares;

        return $this;
    }

    public function whereParameters(array $parameters): self
    {
        foreach ($parameters as $key => $value) {
            $parameters[$key] = self::format($value);
        }

        self::$tmp_routes[self::$route]['parameters'] = $parameters;

        return $this;
    }

    public function bindParameters(array $parameters): self
    {
        self::$tmp_routes[self::$route]['bindings'] = $parameters;

        return $this;
    }

    public function byMiddleware(array|string $middlewares): self
    {
        $middlewares = parse_array($middlewares);

        foreach (self::$tmp_routes as $route => $options) {
            if (isset($options['middlewares'])) {
                self::$tmp_routes[$route]['middlewares'] = array_merge($middlewares, $options['middlewares']);
            } else {
                self::$tmp_routes[$route]['middlewares'] = $middlewares;
            }
        }

        return $this;
    }

    public function byPrefix(string $prefix): self
    {
        if ($prefix[-1] === '/') {
            $prefix = rtrim($prefix, '/');
        }

        foreach (self::$tmp_routes as $route => $options) {
            list($method, $uri) = explode(' ', $route, 2);
            $_route = implode(' ', [$method, $prefix . $uri]);
            $_route = self::format($_route);
            self::$tmp_routes = self::updateRoute($route, $_route);
        }

        return $this;
    }

    public function byName(string $name): self
    {
        foreach (self::$tmp_routes as $route => $options) {
            if (isset($options['name'])) {
                self::$tmp_routes[$route]['name'] = $name . '.' . $options['name'];
            } else {
                self::$tmp_routes[$route]['name'] = $name;
            }
        }

        return $this;
    }

    public function byController(string $controller): self
    {
        foreach (self::$tmp_routes as $route => $options) {
            if (isset($options['handler'])) {
                self::$tmp_routes[$route]['handler'] = [$controller, $options['handler']];
            } else {
                self::$tmp_routes[$route]['handler'] = $controller;
            }
        }

        return $this;
    }

    public function register(): void
    {
        if (empty(self::$tmp_routes)) {
            return;
        }

        self::$routes += self::$tmp_routes;
        self::$tmp_routes = [];
    }

    public static function format(string $route): string
    {
        $patterns = [
            '/\b' . RouteParameter::ALPHA . '\b/' => '([a-zA-Z-_]+)',
            '/\b' . RouteParameter::NUMBER . '\b/' => '(\d+)',
            '/\b' . RouteParameter::ALPHA_NUMERIC . '\b/' => '([a-zA-Z0-9-_]+)',
            '/\b' . RouteParameter::ANY . '\b/' => '([^/]+)',
        ];

        return preg_replace(array_keys($patterns), array_values($patterns), $route);
    }

    /**
     * @link   https://thisinterestsme.com/php-replace-array-key/
     */
    private static function updateRoute(string $old, string $new): array
    {
        $array_keys = array_keys(self::$tmp_routes);
        $old_key_index = array_search($old, $array_keys);
        $array_keys[$old_key_index] = $new;

        return array_combine($array_keys, self::$tmp_routes);
    }

    /**
     * @throws RoutesPathsNotDefinedException
     */
    public static function getAll(): array
    {
        self::load();
        return self::$routes;
    }

    protected static function loadFromAttributes(): void
    {
        $controllers = Discover::in(config('storage.controllers'))->classes()->get();

        foreach ($controllers as $controller) {
            $reflectionClass = new ReflectionClass($controller);
            $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $attributes = $method->getAttributes(RouteAttribute::class);

                foreach ($attributes as $attribute) {
                    $attribute = $attribute->newInstance();
                    $route = self::match($attribute->methods, $attribute->uri ?? '/' . $method->getName(), [$controller, $method->getName()]);

                    if ($attribute->middlewares) {
                        $route->middleware($attribute->middlewares);
                    }

                    if ($attribute->name) {
                        $route->name($attribute->name);
                    }

                    if ($attribute->parameters) {
                        $route->whereParameters($attribute->parameters);
                    }

                    if ($attribute->bindings) {
                        $route->bindParameters($attribute->bindings);
                    }

                    $route->register();
                }
            }
        }
    }

    /**
     * @throws RoutesPathsNotDefinedException
     */
    public static function load(): void
    {
        self::loadFromAttributes();

        if (empty(config('routes')) && empty(self::$routes)) {
            throw new RoutesPathsNotDefinedException();
        }

        if (! empty(config('routes'))) {
            $paths = array_map(function ($path) {
                $path = $path === DIRECTORY_SEPARATOR
                    ? config('storage.routes')
                    : storage(config('storage.routes'))->addPath($path)->getPath();

                return str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $path);
            }, config('routes'));

            foreach ($paths as $path) {
                $routes = storage($path)->addPath('')->getFiles();

                foreach ($routes as $route) {
                    require_once $path . $route;
                }
            }
        }
    }
}

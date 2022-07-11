<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

use Core\Exceptions\RoutesPathsNotDefinedException;
use Core\Support\Storage;
use Core\Http\Response;

/**
 * Manage routes
 */
class Route
{
    protected static string $route;
    public static array $routes = [];
    protected static array $tmp_routes = [];

    private static function add(string $route, $handler): self
    {
        static::$route = static::format($route);
        static::$tmp_routes[static::$route] = ['handler' => $handler];
        return new static();
    }

    public static function get(string $uri, $handler): self
    {
        return static::add('GET ' . $uri, $handler);
    }

    public static function post(string $uri, $handler): self
    {
        return static::add('POST ' . $uri, $handler);
    }
    
    public static function delete(string $uri, $handler): self
    {
        return static::add('DELETE ' . $uri, $handler);
    }
    
    public static function options(string $uri, $handler): self
    {
        return static::add('OPTIONS ' . $uri, $handler);
    }
    
    public static function patch(string $uri, $handler): self
    {
        return static::add('PATCH ' . $uri, $handler);
    }
    
    public static function put(string $uri, $handler): self
    {
        return static::add('PUT ' . $uri, $handler);
    }
    
    public static function any(string $uri, $handler): self
    {
        return static::add('GET|POST|DELETE|PUT|OPTIONS|PATCH ' . $uri, $handler);
    }
    
    public static function all(string $name, string $controller, array $excepts = []): self
    {
        return self::group(function() use ($name, $excepts) {
            if (!in_array('index', $excepts)) self::get('/' . $name, 'index')->name('index');
            if (!in_array('store', $excepts)) self::post('/' . $name, 'store')->name('store');
            if (!in_array('update', $excepts)) self::match('PATCH|PUT', '/' . $name . '/{id:num}', 'update')->name('update');
            if (!in_array('show', $excepts)) self::get('/' . $name . '/{id:num}', 'show')->name('show');
            if (!in_array('edit', $excepts)) self::get('/' . $name . '/{id:num}/edit', 'edit')->name('edit');
            if (!in_array('delete', $excepts)) self::delete('/' . $name . '/{id:num}', 'delete')->name('delete');
        })->byController($controller)->byName($name);
    }
    
    public static function match(string $methods, string $uri, $handler): self
    {
        return static::add($methods . ' ' . $uri, $handler);
    }

    public static function view(string $uri, string $view, array $params = []): self
    {
        return static::get($uri, function (Response $response) use ($view, $params) {
            $response->view($view, $params)->send();
        });
    }

    public function name(string $name): self
    {
        static::$tmp_routes[static::$route]['name'] = $name;
        return $this;
    }

    public static function group($callback): self
    {
        call_user_func($callback);
        return new static();
    }
    
    public function middleware(string ...$middlewares): self
    {
        static::$tmp_routes[static::$route]['middlewares'] = $middlewares;
        return $this;
    }
    
    public function byMiddleware(string ...$middlewares): self
    {
        foreach (static::$tmp_routes as $route => $options) {
            if (isset($options['middlewares'])) {
                static::$tmp_routes[$route]['middlewares'] = array_merge($middlewares, $options['middlewares']);
            } else {
                static::$tmp_routes[$route]['middlewares'] = $middlewares;
            }
        }

        return $this;
    }
    
    public function byPrefix(string $prefix): self
    {
        if ($prefix[-1] === '/') $prefix = rtrim($prefix, '/');

        foreach (static::$tmp_routes as $route => $options) {
            list($method, $uri) = explode(' ', $route, 2);

            $_route = implode(' ', [$method, $prefix . $uri]);
            $_route = static::format($_route);
            static::$tmp_routes = static::update($route, $_route);
        }

        return $this;
    }
    
    public function byName(string $name): self
    {
        foreach (static::$tmp_routes as $route => $options) {
            if (isset($options['name'])) {
                static::$tmp_routes[$route]['name'] = $name . '.' . $options['name'];
            } else {
                static::$tmp_routes[$route]['name'] = $name;
            }
        }

        return $this;
    }

    public function byController(string $controller): self
    {
        foreach (static::$tmp_routes as $route => $options) {
            if (isset($options['handler'])) {
                static::$tmp_routes[$route]['handler'] = [$controller, $options['handler']];
            } else {
                static::$tmp_routes[$route]['handler'] = $controller;
            }
        }

        return $this;
    }
    
    public function register()
    {
        if (empty(static::$tmp_routes)) return;

        static::$routes += static::$tmp_routes;
        static::$tmp_routes = [];
    }
    
    private static function format(string $route): string
    {
        list($method, $uri) = explode(' ', $route, 2);

        if (empty($uri)) $uri = '/';

        if (strlen($uri) > 1) {
            if ($uri[0] !== '/') {
                $uri = '/' . $uri;
            }
        }

        $uri = preg_replace('/{([a-zA-Z-_]+)}/i', 'any', $uri);
        $uri = preg_replace('/{([a-zA-Z-_]+):([^\}]+)}/i', '$2', $uri);
        $uri = preg_replace('/\bstr\b/', '([a-zA-Z-_]+)', $uri);
        $uri = preg_replace('/\bnum\b/', '(\d+)', $uri);
        $uri = preg_replace('/\bany\b/', '([^/]+)', $uri);

        return implode(' ', [$method, $uri]);
    }

    /**
     * Update formatted route
     * 
     * @link   https://thisinterestsme.com/php-replace-array-key/
     */
    private static function update(string $old, string $new): array
    {
        $array_keys = array_keys(static::$tmp_routes);
        $old_key_index = array_search($old, $array_keys);
        $array_keys[$old_key_index] = $new;

        return array_combine($array_keys, static::$tmp_routes);
    }

    public static function load()
    {
        if (empty(config('routes.paths'))) {
            throw new RoutesPathsNotDefinedException();
        }

        $paths = array_map(function ($path) {
            $path = Storage::path(config('storage.routes'))
                ->addPath($path, '')
                ->getPath();

            return str_replace(['//', '//"'], ['/', '/"'], $path);
        }, config('routes.paths'));

        foreach ($paths as $path) {
            $routes = Storage::path($path)->getFiles();

            foreach ($routes as $route) {
                require_once config('storage.routes') . $route;
            }
        }
    }
}

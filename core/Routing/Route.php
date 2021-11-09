<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

use Core\Support\Storage;
use Core\Http\Response\Response;

/**
 * Manage routes
 */
class Route
{
    protected static $route;
    public static $routes = [];
    protected static $tmp_routes = [];

    private static function add(string $route, $handler): self
    {
        static::$route = self::format($route);
        static::$tmp_routes[static::$route] = ['handler' => $handler];
        return new self();
    }

    public static function get(string $uri, $handler): self
    {
        return self::add('GET ' . $uri, $handler);
    }

    public static function post(string $uri, $handler): self
    {
        return self::add('POST ' . $uri, $handler);
    }
    
    public static function delete(string $uri, $handler): self
    {
        return self::add('DELETE ' . $uri, $handler);
    }
    
    public static function options(string $uri, $handler): self
    {
        return self::add('OPTIONS ' . $uri, $handler);
    }
    
    public static function patch(string $uri, $handler): self
    {
        return self::add('PATCH ' . $uri, $handler);
    }
    
    public static function put(string $uri, $handler): self
    {
        return self::add('PUT ' . $uri, $handler);
    }
    
    public static function any(string $uri, $handler): self
    {
        return self::add('GET|POST|DELETE|PUT|OPTIONS|PATCH ' . $uri, $handler);
    }
    
    public static function match(string $methods, string $uri, $handler): self
    {
        return self::add($methods . ' ' . $uri, $handler);
    }

    public static function view(string $uri, string $view, array $params = []): self
    {
        return self::get($uri, function (Response $response) use ($view, $params) {
            $response->view($view, $params);
        });
    }

    public function name(string $name): self
    {
        static::$tmp_routes[static::$route] += ['name' => $name];
        return $this;
    }
    
    public function middlewares(string ...$middlewares): self
    {
        static::$tmp_routes[static::$route] += ['middlewares' => $middlewares];
        return $this;
    }
    
    public static function groupMiddlewares(array $middlewares, $callback): self
    {
        call_user_func($callback);

        foreach (static::$tmp_routes as $route => $options) {
            static::$tmp_routes[$route] += ['middlewares' => $middlewares];
        }

        return new self();
    }
    
    public static function groupPrefix(string $prefix, $callback): self
    {
        call_user_func($callback);
        
        foreach (static::$tmp_routes as $route => $options) {
            $_route = self::format(self::addPrefix($prefix, $route));
            static::$tmp_routes = self::update($route, $_route);
        }

        return new self();
    }
    
    public function register()
    {
        if (empty(static::$tmp_routes)) return;

        static::$routes += static::$tmp_routes;
        static::$tmp_routes = [];
    }
    
    private static function addPrefix(string $prefix, string $route)
    {
        if ($prefix[-1] === '/') $prefix = rtrim($prefix, '/');

        list($method, $uri) = explode(' ', $route, 2);

        return implode(' ', [$method, $prefix . $uri]);
    }
    
    private static function format(string $route)
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
     * Update formated route
     * 
     * @link   https://thisinterestsme.com/php-replace-array-key/
     */
    private static function update(string $old, string $new)
    {
        $array_keys = array_keys(static::$tmp_routes);
        $old_key_index = array_search($old, $array_keys);
        $array_keys[$old_key_index] = $new;
        $new_array = array_combine($array_keys, static::$tmp_routes);

        return $new_array;
    }

    public static function loadFiles()
    {
        $routes = Storage::path(config('storage.routes'))->getFiles();

        foreach ($routes as $route) {
            require_once config('storage.routes') . $route;
        }
    }
}

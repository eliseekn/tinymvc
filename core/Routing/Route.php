<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

/**
 * Manage routes
 */
class Route
{
    /**
     * request route
     * 
     * @var array
     */
    protected static $route;

    /**
     * routes paths
     * 
     * @var array
     */
    public static $routes = [];

    /**
     * temporary routes paths
     * 
     * @var array
     */
    protected static $tmp_routes = [];

    /**
     * add route
     *
     * @param  string $route
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function add(string $route, $callback): self
    {
        static::$route = self::format($route);
        static::$tmp_routes[static::$route] = ['handler' => $callback];
        return new self();
    }

    /**
     * add route with GET method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function get(string $uri, $callback): self
    {
        return self::add('GET ' . $uri, $callback);
    }

    /**
     * add route with POST method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function post(string $uri, $callback): self
    {
        return self::add('POST ' . $uri, $callback);
    }
    
    /**
     * add route with DELETE method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function delete(string $uri, $callback): self
    {
        return self::add('DELETE ' . $uri, $callback);
    }
    
    /**
     * add route with PUT method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function put(string $uri, $callback): self
    {
        return self::add('PUT ' . $uri, $callback);
    }
    
    /**
     * add route with OPTION method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function options(string $uri, $callback): self
    {
        return self::add('OPTIONS ' . $uri, $callback);
    }
    
    /**
     * add route with PATCH method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function patch(string $uri, $callback): self
    {
        return self::add('PATCH ' . $uri, $callback);
    }
    
    /**
     * add route with all methods
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function any(string $uri, $callback): self
    {
        return self::add('* ' . $uri, $callback);
    }
    
    /**
     * add route with one or more methods
     *
     * @param  string $methods
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function match(string $methods, string $uri, $callback): self
    {
        return self::add($methods . ' ' . $uri, $callback);
    }

    /**
     * set route name
     *
     * @param  string $name
     * @return \Core\Routing\Route
     */
    public function name(string $name): self
    {
        static::$tmp_routes[static::$route] += ['name' => $name];
        return $this;
    }
    
    /**
     * add middlewares to route
     *
     * @param  string[] $middlewares
     * @return \Core\Routing\Route
     */
    public function middlewares(string ...$middlewares): self
    {
        static::$tmp_routes[static::$route] += ['middlewares' => $middlewares];
        return $this;
    }
    
    /**
     * set route as locked
     *
     * @param  string[] $roles
     * @return \Core\Routing\Route
     */
    public function lock(string ...$roles): self
    {
        static::$tmp_routes[static::$route] += ['locked' => $roles];
        return $this;
    }

    /**
     * group routes by middlewares
     *
     * @param  array $middlewares
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function groupMiddlewares(array $middlewares, $callback): self
    {
        call_user_func($callback);

        foreach (static::$tmp_routes as $route => $options) {
            static::$tmp_routes[$route] += ['middlewares' => $middlewares];
        }

        return new self();
    }
    
    /**
     * group routes by prefix
     *
     * @param  string $prefix
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function groupPrefix(string $prefix, $callback): self
    {
        call_user_func($callback);
        
        foreach (static::$tmp_routes as $route => $options) {
            $_route = self::format(self::prefix($prefix, $route));
            static::$tmp_routes = self::replace_uri($route, $_route);
        }

        return new self();
    }
    
    /**
     * apply multiple groups to routes
     *
     * @param  array $groups
     * @param  \Closure $callback
     * @return \Core\Routing\Route
     */
    public static function group(array $groups, $callback): self
    {
        $route = new self();

        if (array_key_exists('prefix', $groups)) {
            $route->groupPrefix($groups['prefix'], $callback);
        }

        if (array_key_exists('middlewares', $groups)) {
            $route->groupMiddlewares($groups['middlewares'], $callback);
        }

        return $route;
    }

    /**
     * register routes
     *
     * @return void
     */
    public function register(): void
    {
        static::$routes += static::$tmp_routes;
        static::$tmp_routes = [];
    }
    
    /**
     * add prefix to uri
     *
     * @param  string $prefix
     * @param  string $route
     * @return string
     */
    private static function prefix(string $prefix, string $route): string
    {
        if ($prefix[-1] === '/') {
            $prefix = rtrim($prefix, '/');
        }

        list($method, $uri) = explode(' ', $route, 2);

        return implode(' ', [$method, $prefix . $uri]);
    }
    
    /**
     * format route
     *
     * @param  string $route
     * @return string
     */
    private static function format(string $route): string
    {
        list($method, $uri) = explode(' ', $route, 2);

        if (empty($uri)) {
            $uri = '/';
        }

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
     * replace uri key in routes
     *
     * @param  mixed $old
     * @param  mixed $new
     * @return array
     * @link   https://thisinterestsme.com/php-replace-array-key/
     */
    private static function replace_uri(string $old, string $new): array
    {
        $array_keys = array_keys(static::$tmp_routes);
        $old_key_index = array_search($old, $array_keys);
        $array_keys[$old_key_index] = $new;
        $new_array = array_combine($array_keys, static::$tmp_routes);

        return $new_array;
    }
}

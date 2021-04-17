<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use App\Http\Middlewares\CsrfProtection;
use App\Http\Middlewares\SanitizeInputs;

/**
 * Manage routes
 */
class Route
{
    /**
     * request uri
     * 
     * @var array
     */
    public static $uri;

    /**
     * routes paths
     * 
     * @var array
     */
    public static $routes = [];

    /**
     * middlewares paths
     * 
     * @var array
     */
    private static $middlewares = [];

    /**
     * temporary routes paths
     * 
     * @var array
     */
    private static $tmp_routes = [];

    /**
     * temporary middlewares paths
     * 
     * @var array
     */
    private static $tmp_middlewares = [];

    /**
     * routes names
     * 
     * @var array
     */
    public static $names = [];


    /**
     * add route with GET method
     *
     * @param  string $uri
     * @return \Framework\Routing\Route
     */
    public static function get(string $uri, $callback): self
    {
        static::$uri = self::parse($uri);

        static::$tmp_routes[static::$uri] = [
            'method' => 'GET|HEAD',
            'handler' => $callback
        ];

        return new self();
    }

    /**
     * add route with POST method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function post(string $uri, $callback): self
    {
        static::$uri = self::parse($uri);

        static::$tmp_routes[static::$uri] = [
            'method' => 'POST',
            'handler' => $callback
        ];

        return new self();
    }
    
    /**
     * add route with DELETE method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function delete(string $uri, $callback): self
    {
        static::$uri = self::parse($uri);

        static::$tmp_routes[static::$uri] = [
            'method' => 'DELETE',
            'handler' => $callback
        ];

        return new self();
    }
    
    /**
     * add route with PUT method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function put(string $uri, $callback): self
    {
        static::$uri = self::parse($uri);

        static::$tmp_routes[static::$uri] = [
            'method' => 'PUT',
            'handler' => $callback
        ];

        return new self();
    }
    
    /**
     * add route with OPTION method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function options(string $uri, $callback): self
    {
        static::$uri = self::parse($uri);

        static::$tmp_routes[static::$uri] = [
            'method' => 'OPTIONS',
            'handler' => $callback
        ];

        return new self();
    }
    
    /**
     * add route with PATCH method
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function patch(string $uri, $callback): self
    {
        static::$uri = self::parse($uri);

        static::$tmp_routes[static::$uri] = [
            'method' => 'PATCH',
            'handler' => $callback
        ];

        return new self();
    }
    
    /**
     * add route with all methods
     *
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function any(string $uri, $callback): self
    {
        static::$uri = self::parse($uri);

        static::$tmp_routes[static::$uri] = [
            'method' => 'GET|HEAD|POST|PUT|DELETE|OPTIONS|PATCH',
            'handler' => $callback
        ];

        return new self();
    }
    
    /**
     * add route with one or more methods
     *
     * @param  string $methods
     * @param  string $uri
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function match(string $methods, string $uri, $callback): self
    {
        static::$uri = self::parse($uri);

        static::$tmp_routes[static::$uri] = [
            'method' => $methods,
            'handler' => $callback
        ];

        return new self();
    }

    /**
     * set route parameters types
     *
     * @param  array $parameters
     * @return \Framework\Routing\Route
     */
    public function where(array $parameters): self
    {
        $params = [];

        foreach ($parameters as $parameter => $type) {
            $type = preg_replace('/\bstr\b/', '([a-zA-Z-_]+)', $type);
            $type = preg_replace('/\bnum\b/', '(\d+)', $type);
            $type = preg_replace('/\bany\b/', '([^/]+)', $type);

            $params += [$parameter => $type];
        }

        static::$tmp_routes[static::$uri] += [
            'parameters' => $params
        ];

        return $this;
    }
    
    /**
     * set route name
     *
     * @param  string $name
     * @return \Framework\Routing\Route
     */
    public function name(string $name): self
    {
        static::$tmp_routes[static::$uri] += ['name' => $name];
        self::$names[$name] = static::$uri;
        return $this;
    }
    
    /**
     * add middlewares to route
     *
     * @param  array $middlewares
     * @return \Framework\Routing\Route
     */
    public function middlewares(array $middlewares): self
    {
        static::$tmp_routes[static::$uri] += ['middlewares' => $middlewares];
        static::$tmp_middlewares[static::$uri] = $middlewares;
        return $this;
    }

    /**
     * group routes by middlewares
     *
     * @param  array $middlewares
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function groupMiddlewares(array $middlewares, $callback): self
    {
        call_user_func($callback);

        foreach (static::$tmp_routes as $uri => $options) {
            static::$tmp_routes[$uri] += ['middlewares' => $middlewares];
            static::$tmp_middlewares[$uri] = $middlewares;
        }

        return new self();
    }
    
    /**
     * group routes by prefix
     *
     * @param  string $prefix
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function groupPrefix(string $prefix, $callback): self
    {
        call_user_func($callback);
        
        foreach (static::$tmp_routes as $uri => $options) {
            foreach (static::$tmp_middlewares as $_uri => $middlewares) {
                if ($uri === $_uri) {
                    $__uri = self::parse(self::addPrefix($prefix, $uri));
                    static::$tmp_middlewares = self::replace_array_key($uri, $__uri, static::$tmp_middlewares);
                    static::$tmp_middlewares[$__uri] += $middlewares;
                }
            }
        }

        foreach (static::$tmp_routes as $uri => $options) {
            $_uri = self::parse(self::addPrefix($prefix, $uri));

            static::$tmp_routes = self::replace_array_key($uri, $_uri, static::$tmp_routes);
            static::$tmp_routes[$_uri] += $options;
        }

        return new self();
    }
    
    /**
     * apply multiple groups to routes
     *
     * @param  array $groups
     * @param  \Closure $callback
     * @return \Framework\Routing\Route
     */
    public static function group(array $groups, $callback): self
    {
        $route = new self();

        if (isset($groups['middlewares'])) {
            $route->groupMiddlewares($groups['middlewares'], $callback);
        }

        if (isset($groups['prefix'])) {
            $route->groupPrefix($groups['prefix'], $callback);
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
        self::$routes += static::$tmp_routes;
        self::$middlewares += static::$tmp_middlewares;
        static::$tmp_middlewares = [];
        static::$tmp_routes = [];

        foreach (static::$routes as $uri => $options) {
            if (isset($options['name']) && !empty($options['name'])) {
                foreach (self::$names as $name => $_uri) {
                    if ($name === $options['name']) {
                        self::$names[$name] = $uri;
                    }
                }
            }
        }

        Middleware::add(self::$middlewares);
    }

    /**
     * add prefix to uri
     *
     * @param  string $prefix
     * @param  string $route
     * @return string
     */
    private static function addPrefix(string $prefix, string $route): string
    {
        if ($prefix[-1] !== '/') {
            $prefix = $prefix . '/';
        }

        if (!empty($route)) {
            if ($route[0] === '/') {
                $route = ltrim($route, '/');
            }
        }

        return $prefix . $route;
    }
    
    /**
     * parse
     *
     * @param  mixed $uri
     * @return void
     */
    private static function parse(string $uri): string
    {
        if (empty($uri)) {
            $uri = '/';
        }

        if (strlen($uri) > 1) {
            if ($uri[0] !== '/') {
                $uri = '/' . $uri;
            }
        }

        return $uri;
    }
    
    /**
     * replace_array_key
     *
     * @param  mixed $old
     * @param  mixed $new
     * @param  mixed $arr
     * @return array
     * @link   https://thisinterestsme.com/php-replace-array-key/
     */
    private static function replace_array_key(string $old, string $new, array $arr): array
    {
        $array_keys = array_keys($arr);
        $old_key_index = array_search($old, $array_keys);
        $array_keys[$old_key_index] = $new;
        $new_array = array_combine($array_keys, $arr);

        return $new_array;
    }
}

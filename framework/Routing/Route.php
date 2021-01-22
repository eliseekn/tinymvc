<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

/**
 * Manage routes
 */
class Route
{
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
     * @param  array $options
     * @return void
     */
    public static function add(string $route, array $options): void
    {
        if (!empty($options)) {
            if (empty($route)) {
                $route = '/';
            }

            if (strlen($route) > 1) {
                if ($route[0] !== '/') {
                    $route = '/' . $route;
                }
            }

            self::$routes[$route] = $options;

            if (isset($options['middlewares'])) {
                Middleware::add($route, $options['middlewares']);
            }
        }
    }
        
    /**
     * add route with GET method
     *
     * @param  string $route
     * @param  array $options
     * @return void
     */
    public static function get(string $route, array $options): void
    {
        $options = array_merge(['method' => 'GET'], $options);
        self::add($route, $options);
    }

    /**
     * add route with POST method
     *
     * @param  string $route
     * @param  array $options
     * @return void
     */
    public static function post(string $route, array $options): void
    {
        $options = array_merge(['method' => 'POST'], $options);
        self::add($route, $options);
    }
    
    /**
     * add route with DELETE method
     *
     * @param  string $route
     * @param  array $options
     * @return void
     */
    public static function delete(string $route, array $options): void
    {
        $options = array_merge(['method' => 'DELETE'], $options);
        self::add($route, $options);
    }
    
    /**
     * add route with PUT method
     *
     * @param  string $route
     * @param  array $options
     * @return void
     */
    public static function put(string $route, array $options): void
    {
        $options = array_merge(['method' => 'PUT'], $options);
        self::add($route, $options);
    }
    
    /**
     * add route with OPTION method
     *
     * @param  string $route
     * @param  array $options
     * @return void
     */
    public static function options(string $route, array $options): void
    {
        $options = array_merge(['method' => 'OPTIONS'], $options);
        self::add($route, $options);
    }
    
    /**
     * add route with PATCH method
     *
     * @param  string $route
     * @param  array $options
     * @return void
     */
    public static function patch(string $route, array $options): void
    {
        $options = array_merge(['method' => 'PATCH'], $options);
        self::add($route, $options);
    }
    
    /**
     * add route with all methods
     *
     * @param  string $route
     * @param  array $options
     * @return void
     */
    public static function any(string $route, array $options): void
    {
        $options = array_merge(['method' => 'GET|HEAD|POST|PUT|DELETE|OPTIONS|PATCH'], $options);
        self::add($route, $options);
    }

    /**
     * group routes
     *
     * @param  array $routes
     * @return \Framework\Routing\Route
     */
    public static function group(array $routes): self
    {
        self::$tmp_routes = $routes;
        return new self();
    }
    
    /**
     * add route prefix
     *
     * @param  string $prefix
     * @param  string $route
     * @return string
     */
    private function prefix(string $prefix, string $route): string
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
     * parameters to group by
     *
     * @param  array $options
     * @return void
     */
    public function by(array $options): void
    {
        foreach (self::$tmp_routes as $route => $tmp_options) {
            if (isset($options['prefix']) && !empty($options['prefix'])) {
                $route = $this->prefix($options['prefix'], $route);
            }

            self::add($route, array_merge($tmp_options, $options));
        }
    }
}

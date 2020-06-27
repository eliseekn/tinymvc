<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Routing;

/**
 * Route
 * 
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
            self::$routes[$route] = $options;

            if (isset($options['handler']) && isset($options['middlewares'])) {
                Middleware::add($options['handler'], $options['middlewares']);
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
     * add route with HEAD method
     *
     * @param  string $route
     * @param  array $options
     * @return void
     */
    public static function head(string $route, array $options): void
    {
        $options = array_merge(['method' => 'HEAD'], $options);
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
    public static function option(string $route, array $options): void
    {
        $options = array_merge(['method' => 'OPTION'], $options);
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
        $options = array_merge(['method' => 'GET|HEAD|POST|DELETE|OPTION|PATCH'], $options);
        self::add($route, $options);
    }

    /**
     * group routes
     *
     * @param  array $routes
     * @return mixed
     */
    public static function group(array $routes)
    {
        self::$tmp_routes = $routes;
        return new self();
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
            self::add($route, array_merge($tmp_options, $options));
        }
    }
}

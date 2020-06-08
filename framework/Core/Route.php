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

namespace Framework\Core;

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

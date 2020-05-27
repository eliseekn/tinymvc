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
     * add route path
     *
     * @return void
     */
    public static function add(string $route, array $data): void
    {
        if (!empty($data)) {
            self::$routes[$route] = $data;

            if (isset($data['controller']) && isset($data['middlewares'])) {
                Middleware::add($data['controller'], $data['middlewares']);
            }
        }
    }
    
    /**
     * group routes
     *
     * @param  array $routes
     * @return void
     */
    public static function group(array $routes)
    {
        self::$tmp_routes = $routes;
        return new self();
    }
    
    /**
     * by
     *
     * @param  array $data
     * @return void
     */
    public function by(array $data): void
    {
        foreach (self::$tmp_routes as $route => $tmp_data) {
            self::add($route, array_merge($tmp_data, $data));
        }
    }
}

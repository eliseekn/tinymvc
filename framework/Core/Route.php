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
     * add route path
     *
     * @return void
     */
    public static function add(string $name, array $data)
    {
        self::$routes[$name] = $data;

        if (isset($data['controller']) && isset($data['middlewares'])) {
            Middleware::add($data['controller'], $data['middlewares']);
        }
    }
}

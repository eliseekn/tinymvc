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

namespace Framework\Http;

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
     * routes names
     * 
     * @var array
     */
    public static $names = [];
    
    /**
     * add route GET request
     *
     * @param  string $url url path
     * @param  string $route route path
     * @return void
     */
    public static function get(string $url, string $route)
    {
        self::$routes['GET ' . $url] = $route;
        return new self();
    }

    /**
     * add route POST request
     *
     * @param  string $url url path
     * @param  string $route route path
     * @return void
     */
    public static function post(string $url, string $route)
    {
        self::$routes['POST ' . $url] = $route;
        return new self();
    }

    /**
     * add route DELETE request
     *
     * @param  string $url url path
     * @param  string $route route path
     * @return void
     */
    public static function delete(string $url, string $route)
    {
        self::$routes['DELETE ' . $url] = $route;
        return new self();
    }

    /**
     * add route PUT request
     *
     * @param  string $url url path
     * @param  string $route route path
     * @return void
     */
    public static function put(string $url, string $route)
    {
        self::$routes['PUT ' . $url] = $route;
        return new self();
    }

    /**
     * add route PATCH request
     *
     * @param  string $url url path
     * @param  string $route route path
     * @return void
     */
    public static function patch(string $url, string $route)
    {
        self::$routes['PATCH ' . $url] = $route;
        return new self();
    }


    /**
     * add route OPTIONS request
     *
     * @param  string $url url path
     * @param  string $route route path
     * @return void
     */
    public static function options(string $url, string $route)
    {
        self::$routes['OPTIONS ' . $url] = $route;
        return new self();
    }


    /**
     * add route any request
     *
     * @param  string $url url path
     * @param  string $route route path
     * @return void
     */
    public static function any(string $url, string $route)
    {
        self::$routes['ANY ' . $url] = $route;
        return new self();
    }

    /**
     * set route name
     * 
     * @param  string $name name of route
     * @return mixed
     */
    public function setName(string $name)
    {
        $route = key(array_slice(self::$routes, -1, 1, true));
        self::$names[$name] = self::$routes[$route];
        return $this;
    }

    /**
     * set route name
     * 
     * @param  string $name name of route
     * @return mixed
     */
    public function useMiddlewares(array $middlewares)
    {
        $route = key(array_slice(self::$names, -1, 1, true));
        //self::$names[$name] = self::$routes[$route];
        Middleware::add($route, $middlewares);
    }
}

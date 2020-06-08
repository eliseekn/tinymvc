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

use Exception;

/**
 * Middleware
 * 
 * Events handlers
 */
class Middleware
{
    /**
     * routes middlewares
     * 
     * @var array
     */
    public static $middlewares = [];

    /**
     * middlewares names
     * 
     * @var array
     */
    public static $names = [];

    /**
     * execute middleware
     *
     * @param  string $middleware name of middleware
     * @return void
     */
    public static function execute(string $middleware): void
    {
        if (!isset(self::$names[$middleware])) {
            throw new Exception('Invalid middleware name "' . $middleware . '".');
        }

        $middleware = self::$names[$middleware]; 
        $middleware = 'App\Middlewares\\' . $middleware;

        //check if middleware class exists
        if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
            throw new Exception('Middleware "' . $middleware . '" not found.');
        }

        $middleware = new $middleware();
        $middleware->handle();
    }
    
    /**
     * execute middlewares associated to a given route
     *
     * @param  string $route name of route
     * @return void
     */
    public static function check(string $route): void
    {
        if (array_key_exists($route, self::$middlewares)) {
            foreach (self::$middlewares[$route] as $middleware) {
                self::execute($middleware);
            }
        }
    }

    /**
     * set middleware name
     *
     * @param  string $middleware middleware class
     * @param  string $name name of middleware
     * @return void
     */
    public static function setName(string $middleware, string $name): void
    {
        self::$names[$name] = $middleware; 
    }
    
    /**
     * add middlewares to route
     *
     * @param  string $route name of route
     * @param  array $middlewares
     * @return void
     */
    public static function add(string $route, array $middlewares): void
    {
        self::$middlewares[$route] = $middlewares;
    }
}
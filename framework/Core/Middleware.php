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
        $middleware = self::$names[$middleware]; 
        $middleware = 'App\Http\Middlewares\\' . $middleware;

        //return a 404 error if controller filename not found or action does not exists
        if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
            View::render('error_404');
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
        $route = explode('\\', $route)[3];
        $route = array_search($route, Route::$names, true);

        if (array_key_exists($route, self::$middlewares)) {
            foreach (self::$middlewares[$route] as $middleware) {
                self::execute($middleware);
            }
        }
    }

    /**
     * set middleware name
     *
     * @param  string $name name of middleware
     * @param  string $middleware middleware class
     * @return void
     */
    public static function setName(string $name, string $middleware): void
    {
        self::$names[$name] = $middleware; 
    }
    
    /**
     * add middlewares to route
     *
     * @param  mixed $route
     * @param  mixed $middlewares
     * @return void
     */
    public static function add(string $route, array $middlewares): void
    {
        self::$middlewares[$route] = $middlewares;
    }
}
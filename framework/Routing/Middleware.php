<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Exception;
use Framework\Http\Request;

/**
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
     * execute middleware
     *
     * @param  mixed $middleware
     * @return void
     */
    public static function execute($middleware): void
    {
        $middleware = config($middleware, 'middlewares');

        if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
            throw new Exception('Middleware "' . $middleware . '" not found.');
        }

        call_user_func_array([$middleware, 'handle'], [new Request()]);
    }
    
    /**
     * execute middlewares associated to a given route
     *
     * @param  string
     * @return void
     */
    public static function check(string $route): void
    {
        if (array_key_exists($route, self::$middlewares)) {
            foreach (self::$middlewares[$route] as $middleware) {
                self::execute($middleware, new Request());
            }
        }
    }
    
    /**
     * add middlewares to route
     *
     * @param  mixed $route
     * @param  mixed $middlewares
     * @return void
     */
    public static function add($route = null, $middlewares = null): void
    {
        if (!is_null($route)) {
            self::$middlewares = $route;
        } else if (!is_null($route) && !is_null($middlewares)) {
            self::$middlewares[$route] = $middlewares;
        }
    }
}

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
     * @param  string
     * @param  array $middlewares
     * @return void
     */
    public static function add(string $route, array $middlewares): void
    {
        self::$middlewares[$route] = $middlewares;
    }
}

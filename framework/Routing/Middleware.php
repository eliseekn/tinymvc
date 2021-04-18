<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Exception;
use Framework\System\DependcyInjection;

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
    protected static $middlewares = [];

    /**
     * execute middleware
     *
     * @param  mixed $middleware
     * @return void
     */
    private static function execute($middleware): void
    {
        $middleware = config('middlewares.' . $middleware);

        if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
            throw new Exception('Middleware "' . $middleware . '" not found.');
        }

        (new DependcyInjection())->resolve($middleware, 'handle');
    }
    
    /**
     * execute middlewares associated to a given route
     *
     * @param  string $route
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
        } 
        
        else if (!is_null($route) && !is_null($middlewares)) {
            self::$middlewares[$route] = $middlewares;
        }
    }
}

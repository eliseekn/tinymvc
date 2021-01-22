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
     * @param  string $middleware name of middleware
     * @return void
     */
    public static function execute(string $middleware, Request $request): void
    {
        $middleware = 'App\Middlewares\\' . $middleware;

        //check if middleware class exists
        if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
            throw new Exception('Middleware "' . $middleware . '" not found.');
        }

        $middleware::handle($request);
    }
    
    /**
     * execute middlewares associated to a given route
     *
     * @param  string $route name of route
     * @return void
     */
    public static function check(string $route, Request $request): void
    {
        if (array_key_exists($route, self::$middlewares)) {
            foreach (self::$middlewares[$route] as $middleware) {
                self::execute($middleware, $request);
            }
        }
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

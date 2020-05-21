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

use Framework\Core\View;
use Framework\Core\Controller;

/**
 * Middleware
 * 
 * Handle middlewares
 */
class Middleware
{
    /**
     * middlewares names
     * 
     * @var array
     */
    public static $names = [];

    /**
     * middlewares route and classnames
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
    private function executeMiddleware(string $middleware): void
    {
        $middleware = 'App\Middlewares\\' . $middleware;

        //return a 404 error if controller filename not found or action does not exists
        if (!class_exists($middleware) || !method_exists($middleware, 'handle')) {
            View::render('error_404');
        }

        $middleware = new $middleware();
        $middleware->handle();
    }
    
    /**
     * set middlewate name
     *
     * @param  string $name name of middleware
     * @param  string $middleware middleware classname
     * @return void
     */
    public static function setName(string $name, string $middleware): void
    {
        self::$names[$name] = $middleware; 
    }
    
    /**
     * set middleware to route
     *
     * @param  string $route name of route
     * @param  array $middlewares middlewares names
     * @return void
     */
    public static function setRoute(string $route, array $middlewares): void
    {
        self::$middlewares[$route] = $middlewares;
    }
    
    /**
     * check if middlewares is set for a route
     *
     * @param  string $controller name of controller
     * @param  string $action name of action
     * @return bool
     */
    public static function middlewareExists(string $controller, string $action): bool
    {
        $route = array_search($controller . '@' . $action, Route::$names, true);
        return array_key_exists($route, self::$middlewares);
    }
    
    /**
     * execute first middleware of a route
     *
     * @param  string $route name of route
     * @return bool|void returns false if middleware not found
     */
    public static function first(string $controller, string $action): void
    {
        $route = array_search($controller . '@' . $action, Route::$names, true);
        $middleware = self::$middlewares[$route];
        $middleware = self::$names[$middleware];
        $this->executeMiddleware($middleware);
    }

    /**
     * execute next middleware
     *
     * @return void
     */
    public function next(): void
    {
        $middleware = get_class($this);
        $name = array_search($middleware, self::$names, true);
        $route = array_search($name, self::$middlewares, true);
        $key = array_search($name, self::$middlewares[$route], true);
        $next_middleware = self::$middlewares[$route][$key + 1];
        $middleware = self::$names[$next_middleware];
        $this->executeMiddleware($middleware);
    }

    /**
     * redirect to controller
     *
     * @return void
     */
    public function end(): void
    {
        $middleware = get_class($this);
        $name = array_search($middleware, self::$names, true);
        $route = array_search($name, self::$middlewares, true);
        Controller::redirectToRoute($route);
    }
}
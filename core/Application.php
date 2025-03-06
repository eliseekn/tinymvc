<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core;

use Core\Event\Event;
use Core\Exceptions\ControllerNotFoundException;
use Core\Exceptions\InvalidRouteHandlerException;
use Core\Exceptions\MiddlewareNotFoundException;
use Core\Exceptions\RouteHandlerNotDefinedException;
use Core\Exceptions\RoutesNotDefinedException;
use Core\Exceptions\RoutesPathsNotDefinedException;
use Core\Http\Routing\Route;
use Core\Http\Routing\Router;
use Core\Support\Whoops;

/**
 * Main application.
 */
class Application
{
    /**
     * @throws MiddlewareNotFoundException
     * @throws InvalidRouteHandlerException
     * @throws RoutesNotDefinedException
     * @throws RoutesPathsNotDefinedException
     * @throws ControllerNotFoundException
     * @throws RouteHandlerNotDefinedException
     */
    public function run(): void
    {
        Whoops::register();
        Event::load();
        Route::load();
        Router::dispatch();
    }
}

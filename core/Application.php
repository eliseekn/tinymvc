<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core;

use Core\Event\Event;
use Core\Exceptions\ControllerNotFoundException;
use Core\Exceptions\MiddlewareNotFoundException;
use Core\Exceptions\RouteException;
use Core\Http\Routing\Router;
use Core\Support\Whoops;

/**
 * Main application.
 */
class Application
{
    /**
     * @throws MiddlewareNotFoundException
     * @throws RouteException
     * @throws ControllerNotFoundException
     */
    public function run(): void
    {
        Whoops::register();
        Event::load();
        Router::dispatch();
    }
}

<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core;

use Core\Events\Event;
use Core\Routing\Route;
use Core\Routing\Router;
use Core\Support\Whoops;

/**
 * Main application.
 */
class Application
{
    public function run(): void
    {
        Whoops::register();
        Event::load();
        Route::load();
        Router::dispatch();
    }
}

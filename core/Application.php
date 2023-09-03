<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core;

use Core\Http\Request;
use Core\Routing\Router;
use Core\Support\Whoops;
use Core\Http\Response;
use Core\Events\Event;

/**
 * Main application
 */
class Application
{
    public function run(): void
    {
        Whoops::register();
        Event::loadListeners();
        Router::dispatch(new Request(), new Response());
    }
}

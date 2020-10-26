<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework;

use Framework\Routing\Router;
use Framework\Support\Storage;

/**
 * Main application
 */
class App
{
    /**
     * load routes files
     *
     * @return void
     */
    public function __construct()
    {
        foreach (Storage::path(config('storage.routes'))->getFiles() as $route) {
            require_once config('storage.routes') . $route;
        }
    }
    
    /**
     * start application
     *
     * @return void
     */
    public function start(): void
    {
        new Router();
    }
}
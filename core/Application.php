<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core;

use Core\Http\Request;
use Core\Routing\Router;
use Core\Support\Whoops;
use Core\Support\Storage;
use Core\Support\Exception;

/**
 * Main application
 */
class Application
{
    public function __construct()
    {
        //register whoops error handler
        Whoops::register();

        $routes = Storage::path(config('storage.routes'))->files();

        foreach ($routes as $route) {
            require_once config('storage.routes') . $route;
        }
    }
    
    public function run()
    {
        try {
            Router::dispatch(new Request());
        } 
        
        catch (Exception $e) {
            if (config('errors.log')) {
                save_log('Exception: ' . $e);
            }

            if (config('errors.display')) {
                die($e);
            }
        
            //send 500 response
            render(config('errors.views.500'), [], 500);
        }
    }
}

<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework;

use Framework\Http\Request;
use Framework\Routing\Route;
use Framework\Routing\Router;
use Framework\Support\Whoops;
use Framework\System\Storage;
use Framework\Support\Exception;

/**
 * Main application
 */
class Application
{
    /**
     * load routes files
     *
     * @return void
     */
    public function __construct()
    {
        //register whoops error handler
        Whoops::register();

        $routes = Storage::path(config('storage.routes'))->files();

        foreach ($routes as $route) {
            require_once config('storage.routes') . $route;
        }
    }
    
    /**
     * start application
     *
     * @return void
     */
    public function run(): void
    {
        $request = new Request();

        try {
            Router::dispatch($request, Route::$routes);
        } catch (Exception $e) {
            if (config('errors.log')) {
                save_log('Exception: ' . $e);
            }

            if (config('errors.display')) {
                die($e);
            }
        
            //send 500 response
            if (!empty(config('errors.views.500'))) {
                render(config('errors.views.500'), [], 500);
            }
                
            response()->send('Try to refresh the page or feel free to contact us if the problem persists', [], 500);
        }
    }
}

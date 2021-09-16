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
        Whoops::register();

        $routes = Storage::path(config('storage.routes'))->getFiles();

        foreach ($routes as $route) {
            require_once config('storage.routes') . $route;
        }

        //setup storages
        if (!Storage::path(config('storage.logs'))->isDir()) Storage::path(config('storage.logs'))->createDir('', true);
        if (!Storage::path(config('storage.cache'))->isDir()) Storage::path(config('storage.cache'))->createDir('', true);
        if (!Storage::path(config('storage.uploads'))->isDir()) Storage::path(config('storage.uploads'))->createDir('', true);
        if (!Storage::path(config('storage.sqlite'))->isDir()) Storage::path(config('storage.sqlite'))->createDir('', true);
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
        
            render(config('errors.views.500'), [], 500);
        }
    }
}

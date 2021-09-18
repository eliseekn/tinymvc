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
use Core\Http\Response\Response;

/**
 * Main application
 */
class Application
{
    private $response;
    private $request;

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

        $this->request = new Request();
        $this->response = new Response();
    }
    
    public function run()
    {
        try {
            Router::dispatch($this->request, $this->response);
        } 
        
        catch (Exception $e) {
            if (config('errors.log')) {
                save_log('Exception: ' . $e);
            }

            if (config('errors.display')) {
                die($e);
            }
        
            $this->response->view(config('errors.views.500'), [], 500);
        }
    }
}

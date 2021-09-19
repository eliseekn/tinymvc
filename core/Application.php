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
        $this->request = new Request();
        $this->response = new Response();

        Whoops::register();

        $routes = Storage::path(config('storage.routes'))->getFiles();

        foreach ($routes as $route) {
            require_once config('storage.routes') . $route;
        }
    }
    
    public function run()
    {
        try {
            Router::dispatch($this->request, $this->response);
        } 
        
        catch (Exception $e) {
            if (config('errors.log')) save_log('Exception: ' . $e);
            if (config('errors.display')) die($e);
        
            $this->response->view(config('errors.views.500'), [], 500);
        }
    }
}

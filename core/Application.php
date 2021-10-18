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
use Core\Support\Exception;
use Core\Http\Response\Response;

/**
 * Main application
 */
class Application
{
    public function __construct()
    {
        Whoops::register();
    }
    
    public function execute()
    {
        $response = new Response();

        try { Router::dispatch(new Request(), $response); } 
        
        catch (Exception $e) {
            if (config('errors.log')) save_log('Exception: ' . $e);
            if (config('errors.display')) die($e);
        
            $response->view(config('errors.views.500'), [], [], 500);
        }
    }
}

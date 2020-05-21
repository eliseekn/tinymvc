<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Core;

use Framework\Http\Route;

/**
 * Controller
 * 
 * Main controller class
 */
class Controller
{
    /**
     * get template name
     *
     * @param  string $template template name to load
     * @return void
     */
    public function renderView(string $template, array $data = [])
    {
        View::render($template, $data);
    }

    /**
     * redirect to route
     *
     * @param  string $name route name or Controller@action
     * @return void
     */
    public static function redirectToRoute(string $name): void
    {
        if (array_key_exists($name, Route::$names)) {
            $route = Route::$names[$name];
        }
        
        if (isset($route) && in_array($route, Route::$routes, true)) {
            $url = array_search($route, Route::$routes, true);
            $url = str_replace(['GET /', 'POST /', 'PUT /', 'DELETE /', 'PATCH /', 'OPTIONS /', 'ANY /'], '', $url);
            redirect_to($url);
        } 

        if (in_array($name, Route::$routes, true)) {
            $url = array_search($name, Route::$routes, true);
            $url = str_replace(['GET /', 'POST /', 'PUT /', 'DELETE /', 'PATCH /', 'OPTIONS /', 'ANY /'], '', $url);
            redirect_to($url);
        }

        //route not found
        View::render('error_404');
    }
}

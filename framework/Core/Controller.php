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
}

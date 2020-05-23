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

use League\Plates\Engine;

/**
 * View
 * 
 * Main view class
 */
class View
{    
    /**
     * display view page
     *
     * @param  string $template template name
     * @param  array $data data to include
     * @return void
     */
    public static function render(string $template, array $data = []): void
    {
        $engine = new Engine('templates');
        echo $engine->render($template, $data);
        exit();
    }
}
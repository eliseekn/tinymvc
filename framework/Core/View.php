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
use Framework\Http\Response;
use Framework\Exceptions\FileNotFoundException;

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
        if (!file_exists('templates' . DIRECTORY_SEPARATOR . $template . '.php')) {
            throw new FileNotFoundException(DOCUMENT_ROOT . 'templates' . DIRECTORY_SEPARATOR . $template);
        }

        $engine = new Engine('templates');
        Response::send([], $engine->render($template, $data));
    }
    
    /**
     * render error page
     *
     * @param  string $template name of template
     * @return void
     */
    public static function error(string $template, int $status_code = 404): void
    {
        if (!file_exists('templates' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . $template . '.php')) {
            throw new FileNotFoundException(DOCUMENT_ROOT . 'templates' . DIRECTORY_SEPARATOR . 'errors' . DIRECTORY_SEPARATOR . $template . '.php');
        }

        $engine = new Engine('templates' . DIRECTORY_SEPARATOR . 'errors');
        Response::send([], $engine->render($template), $status_code);
    }
}
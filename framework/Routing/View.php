<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Routing;

use Exception;
use League\Plates\Engine;
use Framework\HTTP\Response;
use Framework\Support\Storage;

/**
 * Main view class
 */
class View
{    
    /**
     * display view template
     *
     * @param  string $view
     * @param  array $data
     * @return void
     */
    public static function render(string $view, array $data = [], int $status_code = 200): void
    {
        if (!Storage::path('views')->isFile($view . '.php')) {
            throw new Exception('File "' . Storage::path('views')->get() . $view . '.php" not found.');
        }

        $engine = new Engine(Storage::path('views')->get());
        Response::send([], $engine->render($view, $data), $status_code);
    }
}

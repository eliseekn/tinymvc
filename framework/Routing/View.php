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
     * get template content
     *
     * @param  string $view
     * @param  array $data
     * @return string
     */
    public static function renderContent(string $view, array $data = [], int $status_code = 200): string
    {
        if (!Storage::path(config('storage.views'))->isFile($view . '.php')) {
            throw new Exception('File "' . Storage::path(config('storage.views'))->get() . $view . '.php" not found.');
        }

        $engine = new Engine(Storage::path(config('storage.views'))->get());
        return $engine->render($view, $data);
    }

    /**
     * display template
     *
     * @param  string $view
     * @param  array $data
     * @return void
     */
    public static function render(string $view, array $data = [], int $status_code = 200): void
    {
        Response::send([], self::renderContent($view, $data, $status_code), $status_code);
    }
}

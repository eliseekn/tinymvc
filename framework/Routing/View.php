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
     * get view content
     *
     * @param  string $view
     * @param  array $data
     * @return string
     */
    public static function getContent(string $view, array $data = []): string
    {
        if (!Storage::path(config('storage.views'))->isFile($view . '.php')) {
            throw new Exception('File "' . Storage::path(config('storage.views'))->get() . $view . '.php" not found.');
        }

        $engine = new Engine(Storage::path(config('storage.views'))->get());

        //session flash data
        $data = array_merge($data, [
            'inputs' => get_inputs(), 
            'errors' => get_errors(), 
            'alerts' => get_alerts()
        ]);

        return $engine->render($view, $data);
    }

    /**
     * display view
     *
     * @param  string $view
     * @param  array $data
     * @return void
     */
    public static function render(string $view, array $data = [], int $status_code = 200): void
    {
        Response::send(self::getContent($view, $data), [], $status_code);
    }
}

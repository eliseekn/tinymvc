<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Exception;
use League\Plates\Engine;
use Framework\Http\Response;
use Framework\Support\Session;
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
        $view = real_path($view);

        if (!Storage::path(config('storage.views'))->isFile($view . '.php')) {
            throw new Exception('File "' . Storage::path(config('storage.views'))->get() . $view . '.php" not found.');
        }

        $engine = new Engine(Storage::path(config('storage.views'))->get());

        return $engine->render($view, array_merge($data, [
            'inputs' => (object) Session::pull('inputs'), 
            'errors' => (object) Session::pull('errors'), 
            'alerts' => Session::pull('alerts')
        ]));
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
        Response::send(self::getContent($view, $data), false, [], $status_code);
    }
}

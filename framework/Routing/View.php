<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Exception;
use League\Plates\Engine;
use Framework\HTTP\Response;
use Framework\Support\Session;
use Framework\Support\Storage;

/**
 * Main view class
 */
class View
{        
    /**
     * get session flash data
     *
     * @return array
     */
    private static function getFlashData(): array
    {
        $data = [
            'inputs' => (object) Session::get('inputs'), 
            'errors' => (object) Session::get('errors'), 
            'alerts' => Session::get('alerts')
        ];

        Session::close('inputs', 'errors', 'alerts');

        return $data;
    }

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
        return $engine->render($view, array_merge($data, self::getFlashData()));
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

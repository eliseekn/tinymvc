<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Exception;
use Twig\Environment;
use Framework\Support\TwigExtensions;
use Framework\Http\Response;
use Framework\Support\Session;
use Framework\Support\Storage;
use Twig\Loader\FilesystemLoader;

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
        $path = Storage::path(config('storage.views'));
        $view = real_path($view) . '.html.twig';

        if (!$path->isFile($view)) {
            throw new Exception('File "' . $path->file($view) . ' not found.');
        }

        $loader = new FilesystemLoader($path->get());
        $twig = new Environment($loader, [
            'cache' => config('storage.cache'),
            'auto_reload' => !config('twig.disable_cache'),
            'debug' => config('errors.display')
        ]);

        $twig->addExtension(new TwigExtensions());

        return $twig->render($view, array_merge($data, [
            'inputs' => (object) Session::pull('inputs'), 
            'errors' => (object) Session::pull('errors'), 
            'alert' => Session::pull('alert')
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
        (new Response())->send(self::getContent($view, $data), false, [], $status_code);
    }
}

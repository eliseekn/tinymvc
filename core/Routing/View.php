<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

use Exception;
use Twig\Environment;
use Core\System\Session;
use Core\System\Storage;
use Twig\Loader\FilesystemLoader;
use Core\System\TwigExtensions;
use Twig\Extension\DebugExtension;

/**
 * Manage views templates
 */
class View
{
    /**
     * get view content
     *
     * @param  string $view
     * @param  array $data
     * @return string
     * 
     * @throws Exception
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
            'cache' => config('twig.disable_cache') ? false : config('storage.cache'),
            'debug' => config('twig.debug')
        ]);

        $twig->addExtension(new TwigExtensions());
        
        if (config('twig.debug')) {
            $twig->addExtension(new DebugExtension());
        }

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
    public static function render(string $view, array $data = [], int $code = 200): void
    {
        response()->send(self::getContent($view, $data), [], $code);
    }
}

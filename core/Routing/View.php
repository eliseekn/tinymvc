<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Routing;

use Exception;
use Twig\Environment;
use Core\Support\Session;
use Core\Support\Storage;
use Twig\Loader\FilesystemLoader;
use Core\Support\TwigExtensions;
use Twig\Extension\DebugExtension;

/**
 * Manage views templates
 */
class View
{
    /**
     * Retrieves view template content
     * 
     * @throws Exception
     */
    public static function getContent(string $view, array $data = [])
    {
        $path = Storage::path(config('storage.views'));
        $view = real_path($view) . '.html.twig';

        if (!$path->isFile($view)) {
            throw new Exception('View template "' . $path->file($view) . ' not found.');
        }

        $loader = new FilesystemLoader($path->getPath());

        $twig = new Environment($loader, [
            'cache' => config('twig.disable_cache') ? false : config('storage.cache'),
            'debug' => config('twig.debug')
        ]);

        $twig->addExtension(new TwigExtensions());
        
        if (config('twig.debug')) $twig->addExtension(new DebugExtension());

        return $twig->render($view, array_merge($data, [
            'inputs' => (object) Session::pull('inputs'), 
            'errors' => (object) Session::pull('errors'), 
            'alert' => Session::pull('alert')
        ]));
    }
}

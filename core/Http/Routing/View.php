<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Routing;

use Core\Exceptions\ViewNotFoundException;
use Core\Support\Storage;
use Core\Support\TwigExtensions;
use Exception;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Manage views templates.
 */
class View
{
    /**
     * Retrieves view template content.
     *
     * @throws ViewNotFoundException
     * @throws Exception
     */
    public static function getContent(string $view, array $data = []): string
    {
        $path = Storage::path(config('storage.views'));
        $view = real_path($view).'.html.twig';

        if (! $path->isFile($view)) {
            throw new ViewNotFoundException($path->file($view));
        }

        $loader = new FilesystemLoader($path->getPath());

        $twig = new Environment($loader, [
            'cache' => config('twig.disable_cache') ? false : config('storage.cache'),
            'debug' => config('twig.debug'),
        ]);

        $twig->addExtension(new TwigExtensions);

        if (config('twig.debug')) {
            $twig->addExtension(new DebugExtension);
        }

        try {
            return $twig->render($view, array_merge($data, [
                'inputs' => (object) session()->pull('inputs'),
                'errors' => (object) session()->pull('errors'),
                'alert' => session()->pull('alert'),
            ]));
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}

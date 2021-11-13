<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

/**
 * Manage twig extensions and filters
 */
class TwigExtensions extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{    
    public function getCustomFunctions()
    {
        $functions = [];

        foreach (config('twig.extensions.functions') as $name => $callable) {
            $functions[] = new \Twig\TwigFunction($name, $callable);
        }

        return $functions;
    }

    public function getCustomFilters()
    {
        $filters = [];

        foreach (config('twig.extensions.filters') as $name => $callable) {
            $filters[] = new \Twig\TwigFilter($name, $callable);
        }

        return $filters;
    }

    public function getCustomGlobals()
    {
        $globals = [];

        foreach (config('twig.extensions.globals') as $name => $callable) {
            $globals[$name] = $callable;
        }

        return $globals;
    }

    public function getGlobals(): array
    {
        return $this->getCustomGlobals();
    }

    public function getFilters()
    {
        return $this->getCustomFilters();
    }

    public function getFunctions()
    {
        return $this->getCustomFunctions() + [
            new \Twig\TwigFunction('auth_attempts_exceeded', 'auth_attempts_exceeded'),
            new \Twig\TwigFunction('auth', 'auth'),
            new \Twig\TwigFunction('csrf_token_input', 'csrf_token_input'),
            new \Twig\TwigFunction('csrf_token_meta', 'csrf_token_meta'),
            new \Twig\TwigFunction('url', 'url'),
            new \Twig\TwigFunction('route', 'route'),
            new \Twig\TwigFunction('assets', 'assets'),
            new \Twig\TwigFunction('resources', 'resources'),
            new \Twig\TwigFunction('storage', 'storage'),
            new \Twig\TwigFunction('current_url', 'current_url'),
            new \Twig\TwigFunction('url_contains', 'url_contains'),
            new \Twig\TwigFunction('config', 'config'),
            new \Twig\TwigFunction('__', '__'),
            new \Twig\TwigFunction('env', 'env'),
            new \Twig\TwigFunction('date', 'date'),
            new \Twig\TwigFunction('method_input', 'method_input'),
        ];
    }
}

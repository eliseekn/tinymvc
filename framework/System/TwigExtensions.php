<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\System;

/**
 * Manage twig extensions and filters
 */
class TwigExtensions extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{    
    /**
     * get custom functions from configuration
     *
     * @return array
     */
    public function getCustomFunctions(): array
    {
        $functions = [];

        foreach (config('twig.extensions.functions') as $name => $callable) {
            $functions[] = new \Twig\TwigFunction($name, $callable);
        }

        return $functions;
    }

    /**
     * get custom filters from configuration
     *
     * @return array
     */
    public function getCustomFilters(): array
    {
        $filters = [];

        foreach (config('twig.extensions.filters') as $name => $callable) {
            $filters[] = new \Twig\TwigFilter($name, $callable);
        }

        return $filters;
    }

    /**
     * get custom globals from configuration
     *
     * @return array
     */
    public function getCustomGlobals(): array
    {
        $globals = [];

        foreach (config('twig.extensions.globals') as $name => $callable) {
            $globals[$name] = $callable;
        }

        return $globals;
    }

    public function getGlobals(): array
    {
        return $this->getCustomGlobals() + [];
    }

    public function getFilters()
    {
        return $this->getCustomFilters() + [];
    }

    public function getFunctions()
    {
        return $this->getCustomFunctions() + [
            new \Twig\TwigFunction('create_cookie', 'create_cookie'),
            new \Twig\TwigFunction('get_cookie', 'get_cookie'),
            new \Twig\TwigFunction('cookie_has', 'cookie_has'),
            new \Twig\TwigFunction('delete_cookie', 'delete_cookie'),
            new \Twig\TwigFunction('create_session', 'create_session'),
            new \Twig\TwigFunction('session_get', 'session_get'),
            new \Twig\TwigFunction('session_has', 'session_has'),
            new \Twig\TwigFunction('close_session', 'close_session'),
            new \Twig\TwigFunction('auth_attempts_exceeded', 'auth_attempts_exceeded'),
            new \Twig\TwigFunction('auth', 'auth'),
            new \Twig\TwigFunction('date_helper', 'date_helper'),
            new \Twig\TwigFunction('escape', 'escape'),
            new \Twig\TwigFunction('generate_csrf_token', 'generate_csrf_token'),
            new \Twig\TwigFunction('csrf_token_input', 'csrf_token_input'),
            new \Twig\TwigFunction('csrf_token_meta', 'csrf_token_meta'),
            new \Twig\TwigFunction('method_input', 'method_input'),
            new \Twig\TwigFunction('valid_csrf_token', 'valid_csrf_token'),
            new \Twig\TwigFunction('url', 'url'),
            new \Twig\TwigFunction('route', 'route'),
            new \Twig\TwigFunction('assets', 'assets'),
            new \Twig\TwigFunction('current_url', 'current_url'),
            new \Twig\TwigFunction('redirect_to', 'redirect_to'),
            new \Twig\TwigFunction('in_url', 'in_url'),
            new \Twig\TwigFunction('real_path', 'real_path'),
            new \Twig\TwigFunction('absolute_path', 'absolute_path'),
            new \Twig\TwigFunction('slugify', 'slugify'),
            new \Twig\TwigFunction('truncate', 'truncate'),
            new \Twig\TwigFunction('random_string', 'random_string'),
            new \Twig\TwigFunction('config', 'config'),
            new \Twig\TwigFunction('get_file_extension', 'get_file_extension'),
            new \Twig\TwigFunction('get_file_name', 'get_file_name'),
            new \Twig\TwigFunction('time_elapsed', 'time_elapsed'),
            new \Twig\TwigFunction('__', '__'),
            new \Twig\TwigFunction('generate_pagination', 'generate_pagination'),
            new \Twig\TwigFunction('save_log', 'save_log'),
            new \Twig\TwigFunction('curl', 'curl'),
            new \Twig\TwigFunction('resources', 'resources'),
            new \Twig\TwigFunction('date', 'date'),
        ];
    }
}
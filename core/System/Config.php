<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\System;

use Dflydev\DotAccessData\Data;

/**
 * Manage configurations
 */
class Config
{
    /**
     * save environnement variables to file
     *
     * @param  array $config
     * @return void
     */
    public static function saveEnv(array $config): void
    {
        if (empty($config)) {
            return;
        }

        $data = '';

        foreach ($config as $key => $value) {
            $data .= "$key=$value";
            putenv($data);
        }

        Storage::path()->writeFile('.env', $data);
    }

    /**
     * load environnement variables
     *
     * @return void
     */
    public static function loadEnv(): void
    {
        if (!Storage::path()->isFile('.env')) {
            return;
        }

        $lines = file(Storage::path()->file('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#')) {
                continue;
            }

            list($key, $value) = explode('=', trim($line), 2);
            putenv("$key=$value");
        }
    }

    /**
     * read environnement variable
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    public static function readEnv(string $key, $default = null)
    {
        $data = getenv($key, true);
        return $data === false || empty($data) ? $default : $data;
    }
    
    /**
     * read configuration from file
     *
     * @param  string $config
     * @param  string $path
     * @param  mixed $default
     * @return mixed
     */
    public static function readFile(string $config, string $path, $default = null)
    {
        $config = require $config;
        $data = new Data($config);

        return $data->get($path, $default);
    }

    /**
     * return translated word or expression
     *
     * @param  string $expr
     * @param  array $data
     * @return string
     */
    public static function readTranslations(string $expr, array $data = []): string
    {
        $translations = require absolute_path('resources.lang') . config('app.lang') . '.php';
        $translated = $translations[$expr];

        foreach ($data as $key => $value) {
            $translated = str_replace('{' . $key  . '}', $value, $translated);
        }

        return $translated;
    }
}

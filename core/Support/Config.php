<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Dflydev\DotAccessData\Data;

/**
 * Manage configurations
 */
class Config
{
    public static function saveEnv(array $config): bool
    {
        if (empty($config)) {
            return false;
        }

        $data = '';

        foreach ($config as $key => $value) {
            $data .= "$key=$value";
            putenv($data);
        }

        return Storage::path()->writeFile('.env', $data);
    }

    public static function loadEnv(): void
    {
        if (!Storage::path()->isFile('.env')) {
            return;
        }

        $lines = file(Storage::path()->file('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (strpos(trim($line), '#')) continue;
            if (trim($line) === '') continue;

            list($key, $value) = explode('=', trim($line), 2);
            putenv("$key=$value");
        }
    }

    public static function readEnv(string $key, $default = null): mixed
    {
        $data = getenv($key, true);
        return $data === false ? $default : $data;
    }
    
    public static function readFile(string $config, string $path, $default = null): mixed
    {
        $config = require $config;
        $data = new Data($config);

        return $data->get($path, $default);
    }

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

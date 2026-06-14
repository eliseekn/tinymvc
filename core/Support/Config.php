<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Core\Exceptions\CoreException;
use Dflydev\DotAccessData\Data;

/**
 * Manage configurations.
 */
class Config
{
    public static function loadEnv(): void
    {
        if (! storage()->isFile('.env')) {
            throw new CoreException('Copy ".env.example" file to ".env" then edit it or run "php console app:setup" console command to setup application');
        }

        $lines = file(storage()->file('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_contains(trim($line), '#') || trim($line) === '') {
                continue;
            }

            [$key, $value] = explode('=', trim($line), 2);
            putenv("$key=$value");
        }
    }

    public static function updateEnv(array $config): bool
    {
        if (empty($config)) {
            return false;
        }

        $data = '';
        $lines = file(storage()->file('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_contains(trim($line), '#') || trim($line) === '') {
                $data .= "\n$line\n";

                continue;
            }

            [$key, $value] = explode('=', trim($line), 2);

            if (array_key_exists($key, $config)) {
                if (is_bool($config[$key])) {
                    $value = $config[$key] ? 'true' : 'false';
                } else {
                    $value = trim((string) $config[$key]);
                }
            }

            $data .= "$key=$value\n";
            putenv("$key=$value");
        }

        return storage()->writeFile('.env', $data);
    }

    public static function readEnv(string $key, $default = null): mixed
    {
        $data = getenv($key, true);

        return $data === false ? $default : $data;
    }

    public static function readFile(string $path, ?string $key = null, $default = null): mixed
    {
        $path = require $path;
        $data = new Data($path);

        if ($key === null) {
            return $data->export();
        }

        return $data->get($key, $default);
    }

    public static function readTranslations(string $expression, array $data = []): string
    {
        $expression = explode('.', $expression, 2);

        $translations = require absolute_path('resources.translations').config('app.lang').DIRECTORY_SEPARATOR.$expression[0].'.php';
        $translated = $translations[$expression[1]];

        foreach ($data as $key => $value) {
            $translated = str_replace('{'.$key.'}', $value, $translated);
        }

        return $translated;
    }
}

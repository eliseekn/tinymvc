<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Core\Support\Storage;

/**
 * Helper that generates stubs files.
 */
class Maker
{
    /**
     * Get stubs storage path.
     */
    public static function stubs(): Storage
    {
        return storage(config('storage.stubs'));
    }

    public static function removeUnderscore(string $word): string
    {
        if (str_contains($word, '_')) {
            $words = explode('_', $word);
            $word = '';

            foreach ($words as $w) {
                $word .= ucfirst($w);
            }
        }

        return $word;
    }

    public static function addNamespace(string $data, string $base, ?string $namespace = null): string
    {
        return is_null($namespace)
            ? str_replace('NAMESPACE', $base, $data)
            : str_replace('NAMESPACE', "$base\\$namespace", $data);
    }

    public static function fixPlural(string $word, bool $remove = false): string
    {
        if (! $remove) {
            if ($word[-1] === 'y') {
                $word = rtrim($word, 'y');
                $word .= 'ies';
            }

            if ($word[-1] !== 's') {
                $word .= 's';
            }
        } else {
            if ($word[-3] === 'ies') {
                $word = rtrim($word, 'ies');
                $word .= 'y';
            }

            if ($word[-1] === 's') {
                $word = rtrim($word, 's');
            }
        }

        return $word;
    }

    public static function generateClass(string $base_name, string $suffix = '', bool $singular = false, bool $force_singular = false): array
    {
        $name = ucfirst(strtolower($base_name));

        if (! $singular) {
            $name = self::fixPlural($name);
        }

        if ($force_singular) {
            $name = self::fixPlural($name, true);
        }

        $name = self::removeUnderscore($name);

        if ($suffix === 'migration') {
            return [lcfirst($name), 'Version'.date('YmdHis')];
        }

        $suffix = self::removeUnderscore($suffix);
        $class = $name.ucfirst($suffix);

        return [lcfirst($name), $class];
    }
}

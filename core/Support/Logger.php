<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Throwable;

/**
 * Logger.
 */
abstract class Logger
{
    public static function log(string $message): void
    {
        $messages = parse_array($message);

        foreach ($messages as $_message) {
            error_log(sprintf('[%s] %s', carbon()->toDateTimeLocalString(), $_message));
        }
    }

    public static function info(string|array $message): void
    {
        self::log('INFO :'.$message);
    }

    public static function error(string|array $message): void
    {
        self::log('ERROR :'.$message);
    }

    public static function warning(string|array $message): void
    {
        self::log('WARNING :'.$message);
    }

    public static function exception(Throwable $e): void
    {
        error_log(sprintf(
            "[%s] %s: %s\nFile: %s\nLine: %d\nTrace:\n%s",
            carbon()->toDateTimeLocalString(),
            $e::class,
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        ));

    }
}

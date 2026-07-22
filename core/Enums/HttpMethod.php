<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Enums;

enum HttpMethod: string
{
    public const GET = 'GET';

    public const POST = 'POST';

    public const PATCH = 'PATCH';

    public const PUT = 'PUT';

    public const OPTIONS = 'OPTIONS';

    public const DELETE = 'DELETE';

    public const ANY = 'GET|POST|DELETE|PUT|OPTIONS|PATCH';

    public static function group(array $methods): string
    {
        return implode('|', $methods);
    }
}

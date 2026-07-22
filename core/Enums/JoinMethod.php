<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Enums;

enum JoinMethod: string
{
    public const INNER = 'INNER ';

    public const LEFT = 'LEFT';

    public const RIGHT = 'RIGHT';

    public const FULL = 'FULL';
}

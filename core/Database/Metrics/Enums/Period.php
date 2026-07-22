<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Metrics\Enums;

enum Period: string
{
    case TODAY = 'today';

    case DAY = 'day';

    case WEEK = 'week';

    case MONTH = 'month';

    case YEAR = 'year';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Metrics\Enums;

enum Aggregate: string
{
    case COUNT = 'count';

    case AVERAGE = 'avg';

    case SUM = 'sum';

    case MAX = 'max';

    case MIN = 'min';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

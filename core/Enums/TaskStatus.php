<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Enums;

enum TaskStatus: string
{
    public const PENDING = 'pending';

    public const RUNNING = 'running';

    public const COMPLETED = 'completed';

    public const FAILED = 'failed';

    public const CANCELLED = 'cancelled';
}

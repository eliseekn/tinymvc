<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Testing\Traits;

use Core\Support\Console;

/**
 * Automatically reset database migrations.
 */
trait RefreshDatabase
{
    public function refreshDatabase(): void
    {
        Console::run('migrations:reset');
    }
}

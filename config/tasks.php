<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use App\Tasks\DeleteExpiredToken;
use App\Tasks\DeleteUnverifiedUsers;
use Core\Enums\TaskDriver;
use Core\Task\Schedule\Schedule;

/*
 * Task configuration
 */

return [
    'driver' => env('TASK_DRIVER', TaskDriver::DATABASE),

    'retries' => [
        'max' => 2,
        'delay' => 60, // in seconds
    ],

    'schedules' => [
        DeleteUnverifiedUsers::class => Schedule::daily(),
        DeleteExpiredToken::class => Schedule::everyHours(),
    ],
];

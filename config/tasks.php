<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use App\Tasks\DeleteUnverifiedUsers;
use Core\Task\Schedule\Schedule;

/*
 * Task configuration
 */

return [
    'driver' => env('TASK_DRIVER', 'database'),

    'retries' => [
        'max' => 2,
        'delay' => 60, // in seconds
    ],

    'schedules' => [
        DeleteUnverifiedUsers::class => Schedule::everyMinutes(),
    ],
];

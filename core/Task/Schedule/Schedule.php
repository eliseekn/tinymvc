<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task\Schedule;

abstract class Schedule
{
    public static function at(int $timestamp): string
    {
        return "at:{$timestamp}";
    }

    public static function daily(string $time = '00:00'): string
    {
        return "daily:{$time}";
    }

    public static function weekly(string $day = 'monday', string $time = '00:00'): string
    {
        return "weekly:{$day}:{$time}";
    }

    public static function monthly(int $month = 1, string $time = '00:00'): string
    {
        return "monthly:{$month }:{$time}";
    }

    public static function everyMinutes(int $minutes = 1): string
    {
        return "every:{$minutes}:minutes";
    }

    public static function everyHours(int $hours = 1): string
    {
        return "every:{$hours}:hours";
    }

    public static function cron(string $expression): string
    {
        return "cron:{$expression}";
    }
}

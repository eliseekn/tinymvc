<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task\Schedule;

use Core\Task\Task;
use Core\Task\TaskInterface;

class Scheduler
{
    public function __construct(public TaskInterface $task)
    {
    }

    protected function schedule(string $executionTime): void
    {
        Task::queue($this->task, $executionTime);
    }

    public function at(int $timestamp): void
    {
        $this->schedule("at:{$timestamp}");
    }

    public function daily(string $time = '00:00'): void
    {
        $this->schedule("daily:{$time}");
    }

    public function weekly(string $day = 'monday', string $time = '00:00'): void
    {
        $this->schedule("weekly:{$day}:{$time}");
    }

    public function monthly(int $day = 1, string $time = '00:00'): void
    {
        $this->schedule("monthly:{$day}:{$time}");
    }

    public function everyMinutes(int $minutes = 1): void
    {
        $this->schedule("every:{$minutes}:minutes");
    }

    public function everyHours(int $hours = 1): void
    {
        $this->schedule("every:{$hours}:hours");
    }

    public function cron(string $expression): void
    {
        $this->schedule("cron:{$expression}");
    }
}

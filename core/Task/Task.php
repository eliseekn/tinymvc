<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task;

use Core\Enums\TaskDriver;
use Core\Enums\TaskStatus;
use Core\Task\Schedule\Scheduler;
use Core\Task\Storage\Database;
use Core\Task\Storage\Redis;
use Core\Task\Storage\StorageInterface;
use Exception;

class Task
{
    protected static ?Task $instance = null;

    protected StorageInterface $storage;

    public function __construct()
    {
        $this->storage = match (static::getDriver()) {
            TaskDriver::REDIS => new Redis,
            default => new Database
        };
    }

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            // @phpstan-ignore-next-line
            static::$instance = new static;
        }

        return static::$instance;
    }

    public static function getDriver(): string
    {
        return config('tasks.driver');
    }

    public static function dispatch(TaskInterface $task): void
    {
        $instance = static::getInstance();
        $id = uniqid();

        $instance->storage->store($id, $task::class, 'at:'.carbon()->timestamp);
        $taskData = $instance->storage->get($id);
        $instance->processTask($taskData);
    }

    public static function queue(TaskInterface $task, string $executionTime): void
    {
        $instance = static::getInstance();
        $instance->storage->store(uniqid(), $task::class, $executionTime);
    }

    public static function process(): array
    {
        $instance = static::getInstance();
        $pendingTasks = $instance->storage->getRunnable();
        $results = [];

        foreach ($pendingTasks as $taskData) {
            $result = $instance->processTask((array) $taskData);

            if (! empty($result)) {
                $results[] = $result;
            }
        }

        return $results;
    }

    public static function cancel(string $id): bool
    {
        $instance = static::getInstance();
        $taskData = $instance->storage->get($id);

        if ($taskData === null || $taskData['status'] !== TaskStatus::PENDING) {
            return false;
        }

        return $instance->storage->markAsCancelled($id);
    }

    public static function cleanup(): bool
    {
        $instance = static::getInstance();

        return $instance->storage->cleanup();
    }

    public static function getAll(): array
    {
        $instance = static::getInstance();

        return $instance->storage->getAll();
    }

    public static function getPending(): array
    {
        $instance = static::getInstance();

        return $instance->storage->getPending();
    }

    private function processTask(array $taskData): array
    {
        $id = $taskData['id'];
        $class = $taskData['class'];
        $retries = $taskData['retries'];
        $executionTime = $taskData['execution_time'];

        if (! $this->shouldRun($taskData)) {
            return [];
        }

        $this->storage->markAsRunning($id, time());

        /** @var \Core\Task\TaskInterface */
        $task = new $class;

        try {
            ob_start();

            $task->handle();

            ob_clean();

            $this->storage->markAsCompleted($id);
            $task->handleCompleted();
        } catch (Exception $e) {
            report($e);

            $this->storage->markAsFailed($id);
            $task->handleFailed();

            if ($this->isRepetitiveTask($executionTime) && $retries >= config('tasks.retries.max')) {
                $this->storage->update($id, ['retries' => 0]);
            }

            if (config('tasks.retries.max') > 0 && $retries < config('tasks.retries.max')) {
                $retryAt = time() + (int) config('tasks.retries.delay');
                $this->storage->markAsPending($id, $retryAt);
                $this->storage->updateRetries($id);
            }
        }

        return $this->storage->get($id);
    }

    public static function schedule(TaskInterface $task): Scheduler
    {
        return new Scheduler($task);
    }

    public static function load(): void
    {
        $tasks = config('tasks.schedules');
        $instance = self::getInstance();

        if (! empty($tasks)) {
            foreach ($tasks as $task => $executionTime) {
                if (! $instance->storage->exists($task, $executionTime)) {
                    $instance::queue(new $task, $executionTime);
                }
            }
        }
    }

    private function isRepetitiveTask(string $executionTime): bool
    {
        return str_starts_with($executionTime, 'daily:')
            || str_starts_with($executionTime, 'weekly:')
            || str_starts_with($executionTime, 'monthly:')
            || str_starts_with($executionTime, 'every:')
            || str_starts_with($executionTime, 'cron:');
    }

    private function shouldRun(array $taskData): bool
    {
        $executionTime = $taskData['execution_time'];
        $lastRun = $taskData['last_run'];
        $retryAt = $taskData['retry_at'];
        $now = time();

        if ($retryAt !== null) {
            if ($now < $retryAt) {
                return false;
            }

            return true;
        }

        if (str_starts_with($executionTime, 'at:')) {
            $time = substr($executionTime, 3);

            return $this->shouldRunAt($time, $lastRun, $now);
        }

        if (str_starts_with($executionTime, 'daily:')) {
            $time = substr($executionTime, 6);

            return $this->shouldRunDaily($time, $lastRun, $now);
        }

        if (str_starts_with($executionTime, 'weekly:')) {
            $parts = explode(':', substr($executionTime, 7));
            $day = $parts[0] ?? 'monday';
            $time = $parts[1] ?? '00:00';

            return $this->shouldRunWeekly($day, $time, $lastRun, $now);
        }

        if (str_starts_with($executionTime, 'monthly:')) {
            $parts = explode(':', substr($executionTime, 8));
            $day = (int) ($parts[0] ?? 1);
            $time = $parts[1] ?? '00:00';

            return $this->shouldRunMonthly($day, $time, $lastRun, $now);
        }

        if (str_starts_with($executionTime, 'every:')) {
            $parts = explode(':', substr($executionTime, 6));
            $interval = (int) ($parts[0] ?? 1);
            $unit = $parts[1] ?? 'minutes';

            return $this->shouldRunEvery($interval, $unit, $lastRun, $now);
        }

        if (str_starts_with($executionTime, 'cron:')) {
            $cronExpression = substr($executionTime, 5);

            return $this->shouldRunCron($cronExpression, $lastRun, $now);
        }

        return false;
    }

    private function shouldRunAt(string $time, ?int $lastRun, int $now): bool
    {
        if ($now < (int) $time) {
            return false;
        }

        if ($lastRun === null) {
            return true;
        }

        return $lastRun < (int) $time;
    }

    private function shouldRunDaily(string $time, ?int $lastRun, int $now): bool
    {
        $targetTime = strtotime("today {$time}");

        if ($now < $targetTime) {
            return false;
        }

        if ($lastRun === null) {
            return true;
        }

        return $lastRun < $targetTime;
    }

    private function shouldRunWeekly(string $day, string $time, ?int $lastRun, int $now): bool
    {
        $targetTime = strtotime("last {$day} {$time}");

        if ($targetTime > $now) {
            $targetTime = strtotime("previous {$day} {$time}");
        }

        if ($now < $targetTime) {
            return false;
        }

        if ($lastRun === null) {
            return true;
        }

        return $lastRun < $targetTime;
    }

    private function shouldRunMonthly(int $day, string $time, ?int $lastRun, int $now): bool
    {
        $currentMonth = date('Y-m');
        $targetTime = strtotime("{$currentMonth}-{$day} {$time}");

        if ($targetTime > $now) {
            $previousMonth = date('Y-m', strtotime('-1 month'));
            $targetTime = strtotime("{$previousMonth}-{$day} {$time}");
        }

        if ($now < $targetTime) {
            return false;
        }

        if ($lastRun === null) {
            return true;
        }

        return $lastRun < $targetTime;
    }

    private function shouldRunEvery(int $interval, string $unit, ?int $lastRun, int $now): bool
    {
        if ($lastRun === null) {
            return true;
        }

        $intervalSeconds = match ($unit) {
            'minutes' => $interval * 60,
            'hours' => $interval * 3600,
            'days' => $interval * 86400,
            default => $interval * 60,
        };

        return ($now - $lastRun) >= $intervalSeconds;
    }

    private function shouldRunCron(string $cronExpression, ?int $lastRun, int $now): bool
    {
        $parts = explode(' ', $cronExpression);

        if (count($parts) !== 5) {
            return false;
        }

        [$minute, $hour, $day, $month, $weekday] = $parts;

        $currentMinute = (int) date('i', $now);
        $currentHour = (int) date('H', $now);
        $currentDay = (int) date('j', $now);
        $currentMonth = (int) date('n', $now);
        $currentWeekday = (int) date('w', $now);

        if (! $this->cronFieldMatches($minute, $currentMinute, 0, 59)) {
            return false;
        }
        if (! $this->cronFieldMatches($hour, $currentHour, 0, 23)) {
            return false;
        }
        if (! $this->cronFieldMatches($day, $currentDay, 1, 31)) {
            return false;
        }
        if (! $this->cronFieldMatches($month, $currentMonth, 1, 12)) {
            return false;
        }
        if (! $this->cronFieldMatches($weekday, $currentWeekday, 0, 6)) {
            return false;
        }

        if ($lastRun !== null) {
            $lastRunMinute = date('Y-m-d H:i', $lastRun);
            $currentRunMinute = date('Y-m-d H:i', $now);

            if ($lastRunMinute === $currentRunMinute) {
                return false;
            }
        }

        return true;
    }

    private function cronFieldMatches(string $field, int $current, int $min, int $max): bool
    {
        if ($field === '*') {
            return true;
        }

        if (str_contains($field, '/')) {
            [$range, $step] = explode('/', $field, 2);
            $step = (int) $step;

            if ($range === '*') {
                return $current >= $min && $current <= $max && ($current - $min) % $step === 0;
            }

            if (str_contains($range, '-')) {
                [$start, $end] = explode('-', $range, 2);
                $start = (int) $start;
                $end = (int) $end;

                return $current >= $start && $current <= $end && ($current - $start) % $step === 0;
            }

            $start = (int) $range;

            return $current >= $start && $current <= $max && ($current - $start) % $step === 0;
        }

        if (str_contains($field, '-')) {
            [$start, $end] = explode('-', $field, 2);

            return $current >= (int) $start && $current <= (int) $end;
        }

        if (str_contains($field, ',')) {
            $values = array_map('intval', explode(',', $field));

            return in_array($current, $values);
        }

        return $current === (int) $field;
    }
}

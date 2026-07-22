<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task\Storage;

use Core\Enums\TaskStatus;
use Core\Services\Redis as RedisService;

class Redis implements StorageInterface
{
    private RedisService $redisService;

    public function __construct()
    {
        $this->redisService = new RedisService;
    }

    public function store(string $id, string $task, string $executionTime): void
    {
        $this->redisService->set($id, [
            'id' => $id,
            'class' => $task,
            'status' => TaskStatus::PENDING,
            'execution_time' => $executionTime,
            'retries' => 0,
            'last_run' => null,
            'retry_at' => null,
        ]);
    }

    public function get(string $id): ?array
    {
        return $this->redisService->get($id);
    }

    public function update(string $id, array $data): bool
    {
        $_data = $this->redisService->get($id);

        if (is_null($_data)) {
            return false;
        }

        $this->redisService->set($id, array_merge($_data, $data));

        return true;
    }

    public function markAsCompleted(string $id): bool
    {
        return $this->update($id, [
            'status' => TaskStatus::COMPLETED,
            'retry_at' => null,
        ]);
    }

    public function markAsFailed(string $id): bool
    {
        return $this->update($id, ['status' => TaskStatus::FAILED]);
    }

    public function markAsCancelled(string $id): bool
    {
        return $this->update($id, ['status' => TaskStatus::CANCELLED]);
    }

    public function markAsRunning(string $id, int $lastRun): bool
    {
        return $this->update($id, [
            'status' => TaskStatus::RUNNING,
            'last_run' => $lastRun,
            'retry_at' => null,
        ]);
    }

    public function markAsPending(string $id, ?int $retryAt = null): bool
    {
        $data = ['status' => TaskStatus::PENDING];

        if ($retryAt !== null) {
            $data['retry_at'] = $retryAt;
        }

        return $this->update($id, $data);
    }

    public function updateRetries(string $id): void
    {
        $task = $this->get($id);

        if (! $task) {
            return;
        }

        $this->update($id, [
            'retries' => $task['retries'] + 1,
        ]);
    }

    public function getPending(): array
    {
        $tasks = $this->redisService->getAll();

        return array_filter($tasks, fn ($task) => $task->status === TaskStatus::PENDING);
    }

    public function getRunnable(): array
    {
        $tasks = $this->redisService->getAll();

        return array_filter($tasks, fn ($task) => $task->status !== TaskStatus::CANCELLED && $task->status !== TaskStatus::RUNNING);
    }

    public function getAll(): array
    {
        return $this->redisService->getAll();
    }

    public function exists(string $task, string $executionTime): bool
    {
        $tasks = $this->redisService->getAll();

        $result = array_filter($tasks, fn ($t) => $t->class === $task && $t->execution_time === $executionTime);

        return ! empty($result);
    }

    public function cleanup(): bool
    {
        $this->redisService->flushall();
        $tasks = $this->redisService->getAll();

        return count($tasks) === 0;
    }
}

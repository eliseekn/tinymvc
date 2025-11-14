<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task\Storage;

use Core\Database\QueryBuilder;
use Core\Enums\TaskStatus;

class Database implements StorageInterface
{
    public function store(string $key, string $task, string $executionTime): void
    {
        QueryBuilder::table('tasks')->insert([
            '_key' => $key,
            'class' => $task,
            'status' => TaskStatus::PENDING,
            'execution_time' => $executionTime,
        ])->execute();
    }

    public function get(string $key): ?array
    {
        $result = QueryBuilder::table('tasks')
            ->select('*')
            ->where('_key', $key)
            ->fetch();

        return (array) $result;
    }

    public function update(string $key, array $data): bool
    {
        $result = QueryBuilder::table('tasks')
            ->update($data)
            ->where('_key', $key)
            ->execute();

        return $result !== false;
    }

    public function markAsCompleted(string $key): bool
    {
        return $this->update($key, ['status' => TaskStatus::COMPLETED]);
    }

    public function markAsFailed(string $key): bool
    {
        return $this->update($key, ['status' => TaskStatus::FAILED]);
    }

    public function markAsCancelled(string $key): bool
    {
        return $this->update($key, ['status' => TaskStatus::FAILED]);
    }

    public function markAsRunning(string $key, int $lastRun): bool
    {
        return $this->update($key, [
            'status' => TaskStatus::RUNNING,
            'last_run' => $lastRun,
        ]);
    }

    public function markAsPending(string $key): bool
    {
        return $this->update($key, [
            'status' => TaskStatus::PENDING,
        ]);
    }

    public function updateRetries(string $key): void
    {
        $task = $this->get($key);

        if (! $task) {
            return;
        }

        $this->update($key, [
            'retries' => $task['retries'] + 1,
        ]);
    }

    public function getPending(): array
    {
        $result = QueryBuilder::table('tasks')
            ->select('*')
            ->where('status', TaskStatus::PENDING)
            ->fetchAll();

        return (array) $result;
    }

    public function getRunnable(): array
    {
        $result = QueryBuilder::table('tasks')
            ->select('*')
            ->whereColumn('status')->notIn([
                TaskStatus::CANCELLED,
                TaskStatus::RUNNING,
            ])
            ->fetchAll();

        return (array) $result;
    }

    public function getAll(): array
    {
        $result = QueryBuilder::table('tasks')
            ->select('*')
            ->fetchAll();

        return (array) $result;
    }

    public function exists(string $task, string $executionTime): bool
    {
        return QueryBuilder::table('tasks')
            ->select('*')
            ->where('class', $task)
            ->and('execution_time', $executionTime)
            ->exists();
    }

    public function cleanup(): int
    {
        return QueryBuilder::table('tasks')
            ->delete()
            ->execute()
            ->rowCount();
    }
}

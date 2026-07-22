<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
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
    public function store(string $id, string $task, string $executionTime): void
    {
        QueryBuilder::table('tasks')->insert([
            'id' => $id,
            'class' => $task,
            'status' => TaskStatus::PENDING,
            'execution_time' => $executionTime,
        ])->execute();
    }

    public function get(string $id): ?array
    {
        $result = QueryBuilder::table('tasks')
            ->select('*')
            ->where('id', $id)
            ->fetch();

        return (array) $result;
    }

    public function update(string $id, array $data): bool
    {
        $result = QueryBuilder::table('tasks')
            ->update($data)
            ->where('id', $id)
            ->execute();

        return $result !== false;
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
        return QueryBuilder::table('tasks')
            ->select('*')
            ->where('status', TaskStatus::PENDING)
            ->fetchAll();
    }

    public function getRunnable(): array
    {
        return QueryBuilder::table('tasks')
            ->select('*')
            ->whereColumn('status')->notIn([
                TaskStatus::CANCELLED,
                TaskStatus::RUNNING,
            ])
            ->fetchAll();
    }

    public function getAll(): array
    {
        return QueryBuilder::table('tasks')
            ->select('*')
            ->fetchAll();
    }

    public function exists(string $task, string $executionTime): bool
    {
        return QueryBuilder::table('tasks')
            ->select('*')
            ->where('class', $task)
            ->and('execution_time', $executionTime)
            ->exists();
    }

    public function cleanup(): bool
    {
        $result = QueryBuilder::table('tasks')
            ->delete()
            ->execute();

        return $result !== false;
    }
}

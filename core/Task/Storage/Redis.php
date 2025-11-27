<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task\Storage;

use Core\Enums\TaskStatus;
use Predis\Client;

class Redis implements StorageInterface
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => config('redis.scheme'),
            'host' => config('redis.host'),
            'port' => config('redis.port'),
            'password' => config('redis.password'),
        ]);
    }

    public function store(string $id, string $task, string $executionTime): void
    {
        $data = serialize([
            'id' => $id,
            'class' => $task,
            'status' => TaskStatus::PENDING,
            'execution_time' => $executionTime,
            'retries' => 0,
            'last_run' => null,
            'retry_at' => null,
        ]);

        $this->client->set($id, $data);
    }

    public function get(string $id): ?array
    {
        $data = $this->client->get($id);

        if (! is_null($data)) {
            return unserialize($data);
        }

        return null;
    }

    public function update(string $id, array $data): bool
    {
        $_data = $this->get($id);

        if (is_null($_data)) {
            return false;
        }

        $data = serialize(array_merge($_data, $data));

        $this->client->set($id, $data);

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
        $tasks = $this->getAll();

        return array_filter($tasks, fn ($task) => $task->status === TaskStatus::PENDING);
    }

    public function getRunnable(): array
    {
        $tasks = $this->getAll();

        return array_filter($tasks, fn ($task) => $task->status !== TaskStatus::CANCELLED && $task->status !== TaskStatus::RUNNING);
    }

    public function getAll(): array
    {
        $result = [];
        $keys = $this->client->keys('*');

        foreach ($keys as $key) {
            $data = $this->client->get($key);

            $result[] = (object) array_merge(['id' => $key], unserialize($data));
        }

        return $result;
    }

    public function exists(string $task, string $executionTime): bool
    {
        $tasks = $this->getAll();

        $result = array_filter($tasks, fn ($t) => $t->class === $task && $t->execution_time === $executionTime);

        return ! empty($result);
    }

    public function cleanup(): bool
    {
        $this->client->flushall();
        $tasks = $this->getAll();

        return count($tasks) === 0;
    }
}

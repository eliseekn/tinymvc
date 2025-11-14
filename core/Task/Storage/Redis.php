<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task\Storage;

class Redis implements StorageInterface
{
    public function store(string $key, string $task, string $executionTime): void {}

    public function get(string $key): ?array
    {
        return [];
    }

    public function update(string $key, string $status, ?int $lastRun = null): void {}

    public function delete(string $key): void {}

    public function getPending(): array
    {
        return [];
    }

    public function getRunnable(): array
    {
        return [];
    }

    public function getAll(): array
    {
        return [];
    }

    public function exists(string $key): bool
    {
        return true;
    }

    public function cleanup(): int
    {
        return 0;
    }
}

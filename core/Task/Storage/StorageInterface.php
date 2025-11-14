<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task\Storage;

interface StorageInterface
{
    public function store(string $key, string $task, string $executionTime): void;

    public function get(string $key): ?array;

    public function update(string $key, array $data): bool;

    public function markAsCompleted(string $key): bool;

    public function markAsFailed(string $key): bool;

    public function markAsCancelled(string $key): bool;

    public function markAsRunning(string $key, int $lastRun): bool;

    public function markAsPending(string $key): bool;

    public function updateRetries(string $key): void;

    public function getPending(): array;

    public function getRunnable(): array;

    public function getAll(): array;

    public function exists(string $task, string $executionTime): bool;

    public function cleanup(): int;
}

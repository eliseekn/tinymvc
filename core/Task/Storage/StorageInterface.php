<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Task\Storage;

interface StorageInterface
{
    public function store(string $id, string $task, string $executionTime): void;

    public function get(string $id): ?array;

    public function update(string $id, array $data): bool;

    public function markAsCompleted(string $id): bool;

    public function markAsFailed(string $id): bool;

    public function markAsCancelled(string $id): bool;

    public function markAsRunning(string $id, int $lastRun): bool;

    public function markAsPending(string $id, ?int $retryAt = null): bool;

    public function updateRetries(string $id): void;

    public function getPending(): array;

    public function getRunnable(): array;

    public function getAll(): array;

    public function exists(string $task, string $executionTime): bool;

    public function cleanup(): bool;
}

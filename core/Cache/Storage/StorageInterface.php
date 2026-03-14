<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache\Storage;

interface StorageInterface
{
    public function store(string $key, mixed $data, ?int $expire = null): void;

    public function get(string $key): mixed;

    public function delete(string $key): void;

    public function deleteAll(): void;

    public function has(string $key): bool;
}

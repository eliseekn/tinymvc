<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache;

interface CacheInterface
{
    public function set(string $key, mixed $data, ?int $time = null): void;

    public function get(string $key): mixed;

    public function delete(string $key): void;

    public function deleteAll(): void;

    public function has(string $key): bool;
}

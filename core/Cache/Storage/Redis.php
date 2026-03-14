<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache\Storage;

use Core\Services\Redis as RedisService;

class Redis implements StorageInterface
{
    private RedisService $redisService;

    public function __construct()
    {
        $this->redisService = new RedisService;
    }

    public function store(string $key, mixed $data, ?int $expire = null): void
    {
        $this->redisService->set($key, $data, $expire);
    }

    public function get(string $key): mixed
    {
        return $this->redisService->get($key);
    }

    public function delete(string $key): void
    {
        $this->delete($key);
    }

    public function deleteAll(): void
    {
        $this->redisService->flushall();
    }

    public function has(string $key): bool
    {
        return $this->redisService->exists($key);
    }
}

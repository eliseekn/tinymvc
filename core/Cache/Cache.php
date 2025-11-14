<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache;

use Core\Cache\Storage\File;
use Core\Cache\Storage\Redis;
use Core\Cache\Storage\StorageInterface;
use Core\Enums\CacheDriver;

class Cache
{
    protected static ?Cache $instance = null;

    protected StorageInterface $cache;

    public function __construct()
    {
        $this->cache = match (static::getDriver()) {
            CacheDriver::REDIS => new Redis,
            default => new File
        };
    }

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            // @phpstan-ignore-next-line
            static::$instance = new static;
        }

        return static::$instance;
    }

    public static function getDriver(): string
    {
        return config('cache.'.config('cache.connection').'.driver');
    }

    public function store(string $key, mixed $data, ?int $time = null): void
    {
        $this->cache->store($key, $data, $time);
    }

    public function get(string $key): mixed
    {
        return $this->cache->get($key);
    }

    public function delete(string $key): void
    {
        $this->cache->delete($key);
    }

    public function deleteAll(): void
    {
        $this->cache->deleteAll();
    }

    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }

    public static function read(string $key, mixed $data, ?int $time = null): mixed
    {
        $cache = static::getInstance();

        if ($cache->has($key)) {
            return $cache->get($key);
        }

        $cache->store($key, $data, $time);

        return $data;
    }
}

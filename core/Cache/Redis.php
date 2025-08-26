<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache;

use Predis\Client;

class Redis implements CacheInterface
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => config('cache.redis.scheme'),
            'host' => config('cache.redis.host'),
            'port' => config('cache.redis.port'),
        ]);
    }

    public function set(string $key, mixed $data, ?int $time = null): void
    {

        $this->client->set($key, serialize($data), $time);
    }

    public function get(string $key): mixed
    {
        $data = $this->client->get($key);

        if (! is_null($data)) {
            return unserialize($data);
        }

        return null;
    }

    public function delete(string $key): void
    {
        $this->client->del($key);
    }

    public function deleteAll(): void
    {
        $this->client->flushall();
    }

    public function has(string $key): bool
    {
        return $this->client->exists($key) === 1;
    }
}

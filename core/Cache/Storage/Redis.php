<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache\Storage;

use Predis\Client;

class Redis implements StorageInterface
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => config('services.redis.scheme'),
            'host' => config('services.redis.host'),
            'port' => config('services.redis.port'),
            'password' => config('services.redis.password'),
        ]);
    }

    public function store(string $key, mixed $data, ?int $time = null): void
    {
        $data = serialize($data);

        if (config('secruty.encryption.cache')) {
            $data = encrypt($data);
        }

        $this->client->set($key, $data, $time);
    }

    public function get(string $key): mixed
    {
        $data = $this->client->get($key);

        if (! is_null($data)) {
            if (config('secruty.encryption.cache')) {
                $data = decrypt($data);
            }

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

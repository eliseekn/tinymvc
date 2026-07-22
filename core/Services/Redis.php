<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Services;

use Predis\Client;

class Redis
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

    public function set(string $key, mixed $data, ?int $expire = null): void
    {
        $data = serialize($data);

        if (config('security.encryption.cache')) {
            $data = encrypt($data);
        }

        $this->client->set($key, $data);

        if (! is_null($expire)) {
            $this->client->expire($key, $expire);
        }
    }

    public function get(string $key): mixed
    {
        $data = $this->client->get($key);

        if (! is_null($data)) {
            if (config('security.encryption.cache')) {
                $data = decrypt($data);
            }

            return unserialize($data);
        }

        return null;
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

    public function delete(string $key): void
    {
        $this->client->del($key);
    }

    public function flushall(): void
    {
        $this->client->flushall();
    }

    public function exists(string $key): bool
    {
        return $this->client->exists($key) === 1;
    }
}

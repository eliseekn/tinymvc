<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache\Storage;

use Core\Support\Storage;

class File implements StorageInterface
{
    protected Storage $storage;

    public function __construct()
    {
        $this->storage = storage(config('storage.cache'));
    }

    public function store(string $key, mixed $data, ?int $expire = null): void
    {
        $data = serialize($data);

        if (config('security.encryption.cache')) {
            $data = encrypt($data);
        }

        $this->storage->writeFile(md5($key), $data);
    }

    public function get(string $key): mixed
    {
        $data = $this->storage->readFile(md5($key));

        if ($data !== '') {
            if (config('security.encryption.cache')) {
                $data = decrypt($data);
            }

            return unserialize($data);
        }

        return null;
    }

    public function delete(string $key): void
    {
        if ($this->storage->isFile(md5($key))) {
            $this->storage->deleteFile(md5($key));
        }
    }

    public function deleteAll(): void
    {
        $keys = $this->storage->getFiles();

        foreach ($keys as $_key) {
            $this->delete($_key);
        }
    }

    public function has(string $key): bool
    {
        return $this->storage->isFile(md5($key));
    }
}

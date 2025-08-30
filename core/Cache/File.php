<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache;

use Core\Support\Encryption;
use Core\Support\Storage;

class File implements CacheInterface
{
    protected Storage $storage;

    public function __construct()
    {
        $this->storage = Storage::path(config('storage.cache'));
    }

    public function set(string $key, mixed $data, ?int $time = null): void
    {
        $data = serialize($data);

        if (config('secruty.encryption.cache')) {
            $data = Encryption::encrypt($data);
        }

        $this->storage->writeFile(md5($key), $data);
    }

    public function get(string $key): mixed
    {
        $data = $this->storage->readFile(md5($key));

        if ($data !== '') {
            if (config('secruty.encryption.cache')) {
                $data = Encryption::decrypt($data);
            }

            return unserialize($data);
        }

        return null;
    }

    public function delete(string $key): void
    {
        $this->storage->deleteFile(md5($key));
    }

    public function deleteAll(): void
    {
        $keys = $this->storage->getFiles();

        foreach ($keys as $key) {
            $this->storage->deleteFile($key);
        }
    }

    public function has(string $key): bool
    {
        return $this->storage->isFile(md5($key));
    }
}

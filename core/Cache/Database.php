<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Cache;

use Core\Database\Connection\Connection;
use Core\Database\QueryBuilder;
use Core\Database\Schema;

class Database implements CacheInterface
{
    protected QueryBuilder $qb;

    public function __construct()
    {
        if (! Connection::getInstance()->tableExists('cache')) {
            Schema::createTable('cache')
                ->addPrimaryKey()->notNull()
                ->addVarChar('_key')->notNull()
                ->addLongText('data')->notNull()
                ->addDefaultTimestamps()
                ->run();
        }

        $this->qb = QueryBuilder::connection()->table('cache');
    }

    public function set(string $key, mixed $data, ?int $time = null): void
    {
        $this->qb->insert([
            '_key' => md5($key),
            'data' => serialize($data),
        ])->execute();
    }

    public function get(string $key): mixed
    {
        $data = $this->qb
            ->selectWhere('_key', md5($key))
            ->fetch();

        if ($data) {
            return unserialize($data);
        }

        return null;
    }

    public function delete(string $key): void
    {
        $this->qb->deleteWhere('_key', md5($key))->execute();
    }

    public function deleteAll(): void
    {
        $this->qb->delete()->execute();
    }

    public function has(string $key): bool
    {
        $data = $this->qb->selectWhere('_key', md5($key))->fetch();

        return $data;
    }
}

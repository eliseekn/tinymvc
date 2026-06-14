<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Connection;

use Core\Database\DB;
use PDO;
use PDOException;
use PDOStatement;

class SQLiteConnection implements ConnectionInterface
{
    protected PDO $pdo;

    protected bool $memory;

    public function __construct(public DB $db)
    {
        $this->memory = isset($db->memory) && $db->memory === true;
        $dsn = $this->memory ? ':memory:' : $db->name;

        try {
            $this->pdo = new PDO('sqlite:'.$dsn);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);

            if ($this->memory) {
                $this->pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
            }
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode(), $e->getPrevious());
        }
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    public function executeStatement(string $query): false|int
    {
        try {
            return $this->pdo->exec($query);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode(), $e->getPrevious());
        }
    }

    public function executeQuery(string $query, ?array $args = null): false|PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare(trim($query));
            $stmt->execute($args);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode(), $e->getPrevious());
        }

        return $stmt;
    }

    public function databaseExists(string $name): bool
    {
        if ($this->memory) {
            return true;
        }

        return storage(config('storage.sqlite'))->isFile($name.'.db');
    }

    public function tableExists(string $name): bool
    {
        $stmt = $this->executeQuery("SELECT name FROM sqlite_master WHERE type='table' AND name = ?", [$name]);

        return $stmt->fetch() !== false;
    }

    public function createDatabase(string $name): void
    {
        if (! $this->memory) {
            storage(config('storage.sqlite'))->writeFile($name.'.db', '');
        }
    }

    public function deleteDatabase(string $name): void
    {
        if (! $this->memory) {
            storage(config('storage.sqlite'))->deleteFile($name.'.db');
        }
    }
}

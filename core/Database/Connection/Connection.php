<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Connection;

use Core\Enums\DatabaseDriver;
use PDO;
use PDOStatement;

/**
 * Manage database connection.
 */
class Connection
{
    protected static ?Connection $instance = null;

    protected ConnectionInterface $db;

    public function __construct(array $db = [])
    {
        $db = empty($db) ? self::getDB() : $db;

        $this->db = match (self::getDriver()) {
            DatabaseDriver::PGSQL => new PostgreSQLConnection(self::getDB()),
            DatabaseDriver::SQLITE => new SQLiteConnection(self::getDB()),
            default => new MySQLConnection(self::getDB())
        };
    }

    public static function getDB(): array
    {
        $driver = self::getDriver();

        if ($driver === DatabaseDriver::SQLITE) {
            return [
                'driver' => DatabaseDriver::SQLITE,
                'name' => config('storage.sqlite').config("database.$driver.name").'.db',
                'memory' => config("database.$driver.memory"),
            ];
        }

        return [
            'driver' => $driver,
            'host' => config("database.$driver.host"),
            'port' => config("database.$driver.port"),
            'name' => config("database.$driver.name"),
            'username' => config("database.$driver.username"),
            'password' => config("database.$driver.password"),
        ];
    }

    public static function getDriver(): string
    {
        return config('app.env') === 'test'
            ? config('database.testing.driver')
            : config('database.driver');
    }

    public static function getDBName(): string
    {
        return self::getDB()['name'];
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            // @phpstan-ignore-next-line
            self::$instance = new static;
        }

        return self::$instance;
    }

    public static function setInstance(Connection $instance): self
    {
        self::$instance = $instance;

        return self::$instance;
    }

    public function executeStatement(string $query): false|int
    {
        return $this->db->executeStatement($query);
    }

    public function executeQuery(string $query, ?array $args = null): false|PDOStatement
    {
        return $this->db->executeQuery($query, $args);
    }

    public function schemaExists(string $name): bool
    {

        return $this->db->schemaExists($name);
    }

    public function tableExists(string $name): bool
    {
        return $this->db->tableExists($name);
    }

    public function createSchema(string $name): void
    {
        $this->db->createSchema($name);
    }

    public function deleteSchema(string $name): void
    {
        $this->db->deleteSchema($name);
    }

    public function getPDO(): PDO
    {
        return $this->db->getPDO();
    }
}

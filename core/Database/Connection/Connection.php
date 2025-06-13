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
use Core\Enums\AppEnv;
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

    protected static ?string $dbConnection = null;

    public function __construct(?string $dbConnection = null)
    {
        static::$dbConnection = $dbConnection;
        $db = static::getDB();

        $this->db = match (static::getDriver()) {
            DatabaseDriver::PGSQL => new PostgreSQLConnection($db),
            DatabaseDriver::SQLITE => new SQLiteConnection($db),
            default => new MySQLConnection($db)
        };
    }

    public static function getDB(): DB
    {
        $driver = static::getDriver();

        $config = config('app.env') === AppEnv::TEST
            ? 'testing.database'
            : 'database.'.static::getDBConnection();

        $db = new DB;
        $db->driver = $driver;
        $db->host = config($config.'.host');
        $db->port = config($config.'.port');
        $db->name = config($config.'.name');
        $db->username = config($config.'.username');
        $db->password = config($config.'.password');

        if ($driver === DatabaseDriver::SQLITE) {
            $db->name = config('storage.sqlite').config($config.'.name').'.db';
            $db->memory = config($config.'.memory');
        }

        if ($driver === DatabaseDriver::MYSQL) {
            $db->charset = config($config.'.charset');
            $db->collation = config($config.'.collation');
            $db->engine = config($config.'.engine');
        }

        if ($driver === DatabaseDriver::PGSQL) {
            $db->encoding = config($config.'.encoding');
        }

        return $db;
    }

    public static function getDBConnection(): string
    {
        if (config('app.env') === AppEnv::TEST) {
            return config('testing.database');
        }

        if (! is_null(static::$dbConnection)) {
            return static::$dbConnection;
        }

        return config('database.connection');
    }

    public static function getDriver(): string
    {
        return config('app.env') === AppEnv::TEST
            ? config('testing.database.driver')
            : config('database.'.static::getDBConnection().'.driver');
    }

    public static function getInstance(?string $dbConnection = null): self
    {
        if (is_null(static::$instance)) {
            // @phpstan-ignore-next-line
            static::$instance = new static($dbConnection);
        }

        return static::$instance;
    }

    public static function setInstance(Connection $instance): self
    {
        static::$instance = $instance;

        return static::$instance;
    }

    public function executeStatement(string $query): false|int
    {
        return $this->db->executeStatement($query);
    }

    public function executeQuery(string $query, ?array $args = null): false|PDOStatement
    {
        return $this->db->executeQuery($query, $args);
    }

    public function databaseExists(string $name): bool
    {

        return $this->db->databaseExists($name);
    }

    public function tableExists(string $name): bool
    {
        return $this->db->tableExists($name);
    }

    public function createDatabase(string $name): void
    {
        $this->db->createDatabase($name);
    }

    public function deleteDatabase(string $name): void
    {
        $this->db->deleteDatabase($name);
    }

    public function getPDO(): PDO
    {
        return $this->db->getPDO();
    }

    public function lastInsertedId(string $table): false|string
    {
        if (static::getDriver() === DatabaseDriver::PGSQL) {
            return $this->getPDO()->lastInsertId($table.'_id_seq');
        }

        return $this->getPDO()->lastInsertId();
    }
}

<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Connection;

use PDO;
use PDOStatement;

/**
 * Manage database connection.
 */
class Connection
{
    /**
     * @var Connection
     */
    protected static ?Connection $instance = null;

    protected MySQLConnection|SQLiteConnection $db;

    private function __construct()
    {
        $driver = config('app.env') === 'test'
            ? config('tests.database.driver')
            : config('database.driver');

        $this->db = match ($driver) {
            'pgsql' => new PostgreSQLConnection(),
            'sqlite' => new SQLiteConnection(),
            default => new MySQLConnection()
        };
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            // @phpstan-ignore-next-line
            self::$instance = new static();
        }

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

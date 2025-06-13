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

class PostgreSQLConnection implements ConnectionInterface
{
    protected PDO $pdo;

    /**
     * @throws PDOException
     */
    public function __construct(public DB $db)
    {
        try {
            $this->pdo = new PDO('pgsql:host='.$db->host.';port='.$db->port.';dbname='.$db->name, $db->username, $db->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    /**
     * @throws PDOException
     */
    public function executeStatement(string $query): false|int
    {
        try {
            return $this->pdo->exec($query);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @throws PDOException
     */
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
        $stmt = $this->executeQuery('SELECT datname FROM pg_catalog.pg_database WHERE datname = ?', [$name]);

        return $stmt->fetch() !== false;
    }

    public function tableExists(string $name): bool
    {
        $stmt = $this->executeQuery('SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1', ['public', $name]);

        return $stmt->fetch() !== false;
    }

    public function createDatabase(string $name): void
    {
        $this->executeStatement("CREATE DATABASE $name ENCODING '".$this->db->encoding."'");
    }

    public function deleteDatabase(string $name): void
    {
        $this->executeStatement('DROP DATABASE IF EXISTS "'.$name.'" CASCADE');
    }
}

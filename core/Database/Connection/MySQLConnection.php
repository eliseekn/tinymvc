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
use Pdo\Mysql;
use PDOException;
use PDOStatement;

class MySQLConnection implements ConnectionInterface
{
    protected PDO $pdo;

    /**
     * @throws PDOException
     */
    public function __construct(public DB $db)
    {
        try {
            $this->pdo = new PDO('mysql:host='.$db->host.';port='.$db->port.';dbname='.$db->name, $db->username, $db->password);
            $this->pdo->setAttribute(Mysql::ATTR_INIT_COMMAND, 'SET NAMES '.$db->charset.' COLLATE '.$db->collation);
            $this->pdo->setAttribute(Mysql::ATTR_FOUND_ROWS, true);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
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
        $stmt = $this->executeQuery('SELECT schema_name FROM information_schema.schemata WHERE schema_name = ?', [$name]);

        return $stmt->fetch() !== false;
    }

    public function tableExists(string $name): bool
    {
        $stmt = $this->executeQuery('SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1', [$this->db->name, $name]);

        return $stmt->fetch() !== false;
    }

    public function createDatabase(string $name): void
    {
        $this->executeStatement('CREATE DATABASE '.$name.' CHARACTER SET '.$this->db->charset.' COLLATE '.$this->db->collation);
    }

    public function deleteDatabase(string $name): void
    {
        $this->executeStatement("DROP DATABASE IF EXISTS $name");
    }
}

<?php

declare(strict_types=1);

namespace Core\Database\Connection;

use PDO;
use PDOException;
use PDOStatement;

class PostgreSQLConnection implements ConnectionInterface
{
    protected PDO $pdo;

    /**
     * @throws PDOException
     */
    public function __construct()
    {
        try {
            $this->pdo = new PDO('pgsql:host=' . config('database.pgsql.host') . ';port=' . config('database.pgsql.port') . ';dbname=' . $this->getDB(), config('database.pgsql.username'), config('database.pgsql.password'));
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

    private function getDB(): string
    {
        return config('app.env') === 'test'
            ? config('database.name') . config('tests.database.suffix')
            : config('database.name');
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

    public function schemaExists(string $name): bool
    {
        $stmt = $this->executeQuery('SELECT schema_name FROM information_schema.schemata WHERE schema_name = ?', [$name]);
        return $stmt->fetch() !== false;
    }

    public function tableExists(string $name): bool
    {
        $stmt = $this->executeQuery('SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ? LIMIT 1', [$this->getDB(), $name]);
        return $stmt->fetch() !== false;
    }

    public function createSchema(string $name): void
    {
        $this->executeStatement('CREATE SCHEMA "' . $name . '"');
    }

    public function deleteSchema(string $name): void
    {
        $this->executeStatement('DROP SCHEMA IF EXISTS "' . $name . '" CASCADE');
    }
}

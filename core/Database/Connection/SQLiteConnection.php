<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database\Connection;

use Core\Support\Storage;
use PDO;
use PDOException;
use PDOStatement;

class SQLiteConnection implements ConnectionInterface
{
	protected PDO $pdo;

    /**
     * @throws PDOException
	 */
	public function __construct()
	{
		try {
            $this->pdo = new PDO('sqlite:' . $this->getDB());
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);

            if (config('database.sqlite.memory')) $this->pdo->setAttribute(PDO::ATTR_PERSISTENT, true);
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
        if (config('app.env') === 'test') {
            return config('storage.sqlite') . config('database.name') . config('tests.database.suffix') . '.db';
        }

        return config('database.sqlite.memory') ? ':memory:'
            : config('storage.sqlite') . config('database.name') . '.db';
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
        return config('database.sqlite.memory') || Storage::path(config('storage.sqlite'))->isFile($name);
    }

    public function tableExists(string $name): bool
    {
        $stmt = $this->executeQuery("SELECT name FROM sqlite_master WHERE type='table' AND name='" . $name . "'");
        return $stmt->fetch() !== false;
    }

    public function createSchema(string $name): void
    {
        if (!config('database.sqlite.memory')) {
            Storage::path(config('storage.sqlite'))->writeFile($name . '.db', '');
        }
    }

    public function deleteSchema(string $name): void
    {
        if (!config('database.sqlite.memory')) {
            Storage::path(config('storage.sqlite'))->deleteFile($name . '.db');
        }
    }
}

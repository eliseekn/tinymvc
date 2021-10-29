<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database\Connection;

use Core\Support\Storage;
use PDO;
use PDOException;

class SQLiteConnection implements ConnectionInterface
{
	/**
	 * @var PDO
	 */
	protected $pdo;

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

    private function getDB()
    {
        if (config('app.env') === 'test') {
            return config('storage.sqlite') . config('database.name') . config('testing.database.suffix') . '.db';
        }

        return config('database.sqlite.memory') ? ':memory:'
            : config('storage.sqlite') . config('database.name') . '.db';
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * @throws PDOException
	 */
    public function executeStatement(string $query)
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
	public function executeQuery(string $query, ?array $args = null)
	{
		$stmt = null;

		try {
			$stmt = $this->pdo->prepare(trim($query));
			$stmt->execute($args);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage(), (int) $e->getCode(), $e->getPrevious());
		}

		return $stmt;
	}

    public function schemaExists(string $name)
    {
        if (config('database.sqlite.memory')) return true;
        return Storage::path(config('storage.sqlite'))->isFile($name);
    }

    public function tableExists(string $name)
    {
        $stmt = $this->executeQuery("SELECT name FROM sqlite_master WHERE type='table' AND name='" . $name . "'");

        return !($stmt->fetch() === false);
    }

    public function createSchema(string $name)
    {
        if (config('database.sqlite.memory')) return;
        Storage::path(config('storage.sqlite'))->writeFile($name . '.db', '');
    }

    public function deleteSchema(string $name)
    {
        if (config('database.sqlite.memory')) return;
        Storage::path(config('storage.sqlite'))->deleteFile($name . '.db');
    }
}

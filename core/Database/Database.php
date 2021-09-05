<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use PDO;
use PDOException;

/**
 * Manage database connection
 */
class Database
{
	/**
	 * @var \Core\Database\Database
	 */
	protected static $instance = null;

	/**
	 * @var PDO
	 */
	protected $pdo;

	/**
     * @throws PDOException
	 */
	private function __construct()
	{
		try {
            $this->pdo = new PDO('mysql:host=' . env('DB_HOST', 'localhost'), config('database.username'), config('database.password'));
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES ' . config('database.charset') . ' COLLATE ' . config('database.collation'));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_FOUND_ROWS, true);
		} catch (PDOException $e) {
            throw new PDOException($e->getMessage());
		}
    }

	public static function connection(): self
	{
		if (is_null(self::$instance)) {
			self::$instance = new static();
        }
        
        return self::$instance;
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

    public function schemaExists(string $db)
    {
        $stmt = $this->executeQuery('SELECT schema_name FROM information_schema.schemata WHERE schema_name = "' . $db .'"');
        return !($stmt->fetch() === false);
    }

}

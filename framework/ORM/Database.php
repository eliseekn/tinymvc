<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

use PDO;
use PDOException;

/**
 * Connection to database
 */
class Database
{
	/**
	 * database class instance
	 * 
	 * @var Database\null
	 */
	protected static $instance = null;

	/**
	 * database connection instance
	 *
	 * @var mixed
	 */
	protected $connection;

	/**
	 * create instance of database connection
	 *
	 * @return void
	 */
	private function __construct()
	{
		try {
            $this->connection = new PDO('mysql:host=' . config('db.host'), config('db.username'), config('db.password'));
            $this->connection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES ' . config('db.charset') . ' COLLATE ' . config('db.collation'));
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		} catch (PDOException $e) {
			if (config('errors.display') == true) {
				die($e->getMessage());
			}
		}
	}

	/**
	 * create single instance of database class
	 *
	 * @return \Framework\ORM\Database
	 */
	public static function getInstance(): self
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
    }

	/**
	 * returns database connection instance
	 *
	 * @return mixed
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * execute sql query
	 *
	 * @return \PDOStatement
	 */
    public function executeQuery(string $query): \PDOStatement
    {
        $stmt = $this->connection->query($query);
        return $stmt;
    }

	/**
	 * execute safe sql query
	 *
	 * @return \PDOStatement
	 */
	public function executeStatement(string $query, array $args): \PDOStatement
	{
		$stmt = null;

		try {
            $this->connection->exec('USE ' . config('db.name'));
			$stmt = $this->connection->prepare(trim($query));
			$stmt->execute($args);
		} catch (PDOException $e) {
			if (config('errors.display') == true) {
				die($e->getMessage());
			}
		}

		return $stmt;
	}

	/**
	 * retrieves last inserted id
	 *
	 * @return int
	 */
	public function lastInsertedId(): int
	{
		return $this->connection->lastInsertId();
	}
}

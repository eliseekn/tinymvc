<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Database;

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
	protected $pdo;

	/**
	 * create instance of database connection
	 *
	 * @return void
	 */
	private function __construct()
	{
		try {
            $this->pdo = new PDO('mysql:host=' . config('database.host') . ';dbname=' . config('database.database'), config('database.username'), config('database.password'));
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES ' . config('database.charset') . ' COLLATE ' . config('database.collation'));
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		} catch (PDOException $e) {
            throw new PDOException($e->getMessage());
		}
    }

	/**
	 * get database connection instance
	 *
	 * @return \Framework\Database\DB
	 */
	public static function connection(): self
	{
		if (is_null(self::$instance)) {
			self::$instance = new static();
        }
        
        return self::$instance;
    }

	/**
	 * execute sql query
	 *
	 * @return \PDOStatement
	 */
    public function query(string $query): \PDOStatement
    {
        $stmt = null;

        try {
            $stmt = $this->pdo->query($query);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
        }
        
        return $stmt;
    }

	/**
	 * execute safe sql query
	 *
	 * @return \PDOStatement
	 */
	public function statement(string $query, array $args): \PDOStatement
	{
		$stmt = null;

		try {
			$stmt = $this->pdo->prepare(trim($query));
			$stmt->execute($args);
		} catch (PDOException $e) {
			throw new PDOException($e->getMessage());
		}

		return $stmt;
	}
}

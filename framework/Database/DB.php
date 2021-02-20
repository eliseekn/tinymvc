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
class DB
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
            $this->connection = new PDO('mysql:host=' . config('mysql.host'), config('mysql.username'), config('mysql.password'));
            $this->connection->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES ' . config('mysql.charset') . ' COLLATE ' . config('mysql.collation'));
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
     * set database to use
     *
     * @param  string|null $db
     * @return \Framework\Database\DB
     */
    private function setDB(?string $db = null): self
    {
        if (!is_null($db)) {
            $this->connection->exec('USE ' . $db);
        }

        return $this;
    }

	/**
	 * get connection to database instance
	 *
     * @param  string|null $db 
	 * @return \Framework\Database\DB
	 */
	public static function connection(?string $db = null): self
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
        }
        
        return self::$instance->setDB($db);
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
            $stmt = $this->connection->query($query);
		} catch (PDOException $e) {
			if (config('errors.display') == true) {
				die($e->getMessage());
			}
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

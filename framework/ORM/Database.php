<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

use PDO;
use PDOException;

/**
 * Database
 * 
 * Connection to database
 */
class Database
{
	/**
	 * database class instance
	 * 
	 * @var mixed
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
			$this->connection = new PDO(
				DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USERNAME, DB_PASSWORD
			);

			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
			$this->connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		} catch (PDOException $e) {
			if (DISPLAY_ERRORS == true) {
				die($e->getMessage());
			}
		}
	}

	/**
	 * create single instance of database class
	 *
	 * @return mixed
	 */
	public static function getInstance()
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
}

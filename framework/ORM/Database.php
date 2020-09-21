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
			$dsn = 'mysql:host=' . config('database.host') . ';dbname=' . config('database.name') . ';charset=' . config('database.charset');

			$this->connection = new PDO($dsn, config('database.username'), config('database.password'));
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

<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Database
 * 
 * Connection to database
 */
class Database
{
	private static $instance = null;
	private $connection;

	/**
	 * create instance of database connection
	 *
	 * @return void
	 */
	private function __construct()
	{
		try {
			$this->connection = new PDO(
				DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
				DB_USERNAME,
				DB_PASSWORD
			);

			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			$this->connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
		} catch (PDOException $e) {
			if (APP_ENV == 'development') {
				die($e->getMessage()); //display error if connection failed
			}
		}
	}

	/**
	 * create single instance of database class
	 *
	 * @return mixed
	 */
	public static function get_instance(): Database
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
	public function connection()
	{
		return $this->connection;
	}
}

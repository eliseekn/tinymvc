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
 * MySQL management system
 */
class Database
{
	private $connection;

	/**
	 * connect to mysql database
	 *
	 * @param  string $db_host
	 * @param  string $db_username
	 * @param  string $db_password
	 * @param  string $db_name
	 * @return void
	 */
	public function __construct(string $db_host, string $db_username, string $db_password, string $db_name = '')
	{
		$this->connection = mysqli_connect($db_host, $db_username, $db_password, $db_name);

		//display error if connection failed
		if (APP_ENV == 'development') {
			if (mysqli_connect_errno()) {
				die(mysqli_connect_error());
			}
		}

		//set encoding to utf8
		if (!mysqli_set_charset($this->connection, 'utf8')) {
			if (APP_ENV == 'development') {
				if (mysqli_connect_errno()) {
					die(mysqli_connect_error());
				}
			}
		}
	}

	/**
	 * prepare and bind mysql statement
	 *
	 * @param  string $query
	 * @param  array $args
	 * @return mixed
	 */
	private function prepare_query(string $query, array $args)
	{
		$params = array();

		$stmt = mysqli_stmt_init($this->connection);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			return false;
			exit();
		}

		$types = array_reduce(
			$args,
			function ($string, &$arg) use (&$params) {
				$params[] = &$arg;

				if (is_float($arg)) {
					$string .= 'd';
				} elseif (is_integer($arg)) {
					$string .= 'i';
				} elseif (is_string($arg)) {
					$string .= 's';
				} else {
					$string .= 'b';
				}

				return $string;
			},
			''
		);

		array_unshift($params, $types);
		call_user_func_array([$stmt, 'bind_param'], $params);

		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		mysqli_stmt_close($stmt);

		return $result;
	}

	/**
	 * execute sql query
	 *
	 * @param  string $query
	 * @param  array $args
	 * @return mixed
	 */
	public function execute_query(string $query, array $args = [])
	{
		if (!empty($args)) {
			$query_result = $this->prepare_query($query, $args);
		} else {
			$query_result = mysqli_query($this->connection, $query);
		}

		if (APP_ENV == 'development') {
			if (!$query_result) {
				die(mysqli_error($this->connection));
			}
		}

		return $query_result;
	}

	/**
	 * fetch row as an associative array
	 *
	 * @param  mixed $query_result
	 * @return mixed
	 */
	public function fetch_row($query_result)
	{
		return mysqli_fetch_row($query_result);
	}

	/**
	 * fetch row as an enumerated array
	 *
	 * @param  mixed $query_result
	 * @return mixed
	 */
	public function fetch_assoc($query_result)
	{
		return mysqli_fetch_assoc($query_result);
	}

	/**
	 * retrives rows count
	 *
	 * @param  mixed $query_result
	 * @return int
	 */
	public function num_rows($query_result): int
	{
		return mysqli_num_rows($query_result);
	}

	/**
	 * get last inserted row id
	 *
	 * @return mixed
	 */
	public function last_insert_id()
	{
		return mysqli_insert_id($this->connection);
	}

	/**
	 * sql injection protection
	 *
	 * @param  string $str
	 * @return string
	 */
	public function escape_string(string $str): string
	{
		return mysqli_real_escape_string($this->connection, $str);
	}
}

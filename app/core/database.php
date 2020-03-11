<?php

/**
* TinyMVC
*
* MIT License
*
* Copyright (c) 2019, N'Guessan Kouadio Elisée
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* @author: N'Guessan Kouadio Elisée (eliseekn => eliseekn@gmail.com)
*/

require_once "config.php";

class Database {

	private $connection;

	//connect to database with defined parameters
	public function __construct(string $db_host, string $db_username, string $db_password, string $db_name = "") {
		$this->connection = mysqli_connect($db_host, $db_username, $db_password, $db_name);

		if (APP_ENV == "development") {
			if (mysqli_connect_errno()) {
				die(mysqli_connect_error());
			}
		}

		if (!mysqli_set_charset($this->connection, "utf8")) {
			if (APP_ENV == "development") {
				if (mysqli_connect_errno()) {
					die(mysqli_connect_error());
				}
			}
		}
	}

	//prepare and bind statement
	private function prepare_query(string $query, array $args) {
		$params = array();

		$stmt = mysqli_stmt_init($this->connection);
		if (!mysqli_stmt_prepare($stmt, $query)) {
			return false;
			exit();
		}

		$types = array_reduce($args, function ($string, &$arg) use (&$params) {
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
		}, '');

		array_unshift($params, $types);
        call_user_func_array([$stmt, 'bind_param'], $params); //must use bind_param instead of mysqli_stmt_bind_param -> eliseekn

		mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        return $result;
    }

	//execute mysql query
	public function execute_query(string $query, array $args = []) {
		if (!empty($args)) {
			$query = $this->prepare_query($query, $args);
		} else {
			$query = mysqli_query($this->connection, $query);
		}

		if (APP_ENV == "development") {
			if (!$query) {
				die(mysqli_error($this->connection));
			}
		}

		return $query;
	}

	//fetch row as an associative array
	public function fetch_row($query) {
		return mysqli_fetch_row($query);
	}

	//fetch row as an enumerated array
	public function fetch_assoc($query) {
		return mysqli_fetch_assoc($query);
	}

	//fetch row as both enumerated and associative array
	public function fetch_array($query) {
		return mysqli_fetch_array($query, MYSQL_BOTH);
	}

	//retrives rows count
	public function num_rows($query) {
		return mysqli_num_rows($query);
	}

	//get last inserted row id
	public function insert_id() {
		return mysqli_insert_id($this->connection);
	}

	//sql injection protection
	public function safe_string(string $str) {
		return mysqli_real_escape_string($this->connection, $str);
	}
}

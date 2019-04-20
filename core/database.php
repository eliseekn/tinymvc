<?php

require_once "core/config.php";

class Database {

	private $connection;

	public function __construct($db_host, $db_username, $db_password, $db_name = "") {
		$this->connection = mysqli_connect($db_host, $db_username, $db_password, $db_name);
		mysqli_set_charset($this->connection, "utf8");

		if (DEV) {
			if (mysqli_connect_errno()) {
				die(mysqli_connect_error());
			}
		}
	}

	public function execute($query) {
		$query = mysqli_query($this->connection, $query);

		if (DEV) {
			if (!$query) {
				die(mysqli_error($this->connection));
			}
		}

		return $query;
	}

	public function fetch($query, $mode = MYSQLI_ASSOC) {
		return mysqli_fetch_array($query, $mode);
	}

	public function count($query) {
		return mysqli_num_rows($this->execute($query));
	}

	public function last_insert_id() {
		return mysqli_insert_id($this->connection);
	}

	public function escape_string($str) {
		return mysqli_real_escape_string($this->connection, $str);
	}
}

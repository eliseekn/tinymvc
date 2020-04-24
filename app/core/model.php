<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => model.php (easy database management system)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//use database to execute sql queries
class Model {

	private $db;
	private $query;
	private $params = array();

	//create database instance
	public function __construct() {
		$this->db = new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	}

	//generate sql query string
	//--start
	public function select(...$columns) {
		$this->query = 'SELECT ';

		foreach($columns as $column) {
			$this->query .= "$column, ";
		}

		$this->query = rtrim($this->query, ', ');
		return $this;
	}

	public function select_as(string $column, string $alias) {
		$this->query = "SELECT $column AS $alias";
		return $this;
	}

	public function from(string $table) {
		$this->query .= " FROM $table ";
		return $this;
	}

	public function where(string $column, string $operator, string $value) {
		$this->query .= " WHERE $column $operator ? ";
		$this->params[] = $value;
		return $this;
	}

	public function and(string $column, string $operator, string $value) {
		$this->query .= " AND $column $operator ? ";
		$this->params[] = $value;
		return $this;
	}

	public function or(string $column, string $operator, string $value) {
		$this->query .= " OR $column $operator ? ";
		$this->params[] = $value;
		return $this;
	}

	public function order_by(string $column, string $direction) {
		$this->query .= " ORDER BY $column $direction ";
		return $this;
	}

	public function like(string $column, string $value) {
		$this->query .= " WHERE $column LIKE '%$value%' ";
		return $this;
	}

	public function or_like(string $column, string $value) {
		$this->query .= " OR $column LIKE '%$value%' ";
		return $this;
	}

	public function limit(int $limit, int $offset = 0) {
		$this->query .= " LIMIT $limit";

		if ($offset != 0) {
			$this->query .= ", $offset";
		}

		return $this;
	}

	public function inner_join(string $table, string $second_column, string $first_column) {
		$this->query .= " INNER JOIN $table ON $first_column = $second_column";
		return $this;
	}

	public function left_join(string $table, string $second_column, string $first_column) {
		$this->query .= " LEFT JOIN $table ON $first_column = $second_column";
		return $this;
	}

	public function right_join(string $table, string $second_column, string $first_column) {
		$this->query .= " RIGHT JOIN $table ON $first_column = $second_column";
		return $this;
	}

	public function full_join(string $table, string $second_column, string $first_column) {
		$this->query .= " FULL JOIN $table ON $first_column = $second_column";
		return $this;
	}

	public function set(array $items) {
		$this->query .= " SET ";

		foreach($items as $key => $value) {
			$this->query .= "$key = ?, ";
			$this->params[] = $value;
		}

		$this->query = rtrim($this->query, ', ');
		return $this;
	}
	//--end

	//execute safe sql query
	public function execute_query() {
		$params = array();

		foreach ($this->params as $key => $value) {
			$params[] = $this->db->escape_string($value);
		}

		$query_result = $this->db->execute_query($this->query, $params);
		$this->params = array();

		return $query_result;
	}

	//execute sql insert query
	public function insert(string $table, array $items): int {
		$this->query = "INSERT INTO $table (";

		foreach($items as $key => $value) {
			$this->query .= "$key, ";
		}

		$this->query = rtrim($this->query, ', ');
		$this->query .= ') VALUES (';

		foreach($items as $key => $value) {
			$this->query .= '?, ';
			$this->params[] = $value;
		}

		$this->query = rtrim($this->query, ', ');
		$this->query .= ')';

		$this->execute_query();

		//return last insert id
		return $this->db->last_insert_id();
	}

	//set sql update query
	public function update(string $table) {
		$this->query = "UPDATE $table";
		return $this;
	}

	//set sql delete query
	public function delete() {
		$this->query = 'DELETE';
		return $this;
	}

	//execute query and retrieves results
	public function fetch() {
		$query_result = $this->execute_query();
		$result = $this->db->fetch_assoc($query_result);
		return $result;
	}

	//execute query and retrieves results recursively
	public function fetch_array() {
		$query_result = $this->execute_query();
		$results = array();
		
		while ($row = $this->db->fetch_assoc($query_result)) {
			$results[] = $row;
		}

		return $results;
	}

	//retrieves rows number
	public function rows_count(): int {
		$query_result = $this->execute_query();
		return $this->db->num_rows($query_result);
	}

	//return query string
	public function query_string() {
		return $this->query;
	}
	
	//set custom sql query string
	public function query(string $query, array $params = []) {
		$this->query = $query;
		$this->params = $params;
		return $this;
	}
}

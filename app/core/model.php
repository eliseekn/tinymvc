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
* @author: N'Guessan Kouadio Elisée AKA eliseekn
* @contact: eliseekn@gmail.com - https://eliseekn.netlify.app
* @version: 1.0.0.0
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

	//generate sql query string
	public function select(string $column = '*') {
		$this->query = "SELECT $column";
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

	public function inner_join(srting $table, string $first_column, string $second_column) {
		$this->query .= " INNER JOIN $table ON $first_column = $second_column";
		return $this;
	}

	public function left_join(srting $table, string $first_column, string $second_column) {
		$this->query .= " LEFT JOIN $table ON $first_column = $second_column";
		return $this;
	}

	public function right_join(srting $table, string $first_column, string $second_column) {
		$this->query .= " RIGHT JOIN $table ON $first_column = $second_column";
		return $this;
	}

	public function full_join(srting $table, string $first_column, string $second_column) {
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
	//

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

	//execute sql update query
	public function update(string $table) {
		$this->query = "UPDATE $table";
		return $this;
	}

	//execute sql delete query
	public function delete() {
		$this->query = 'DELETE';
		return $this;
	}

	//execute query and get result
	public function fetch() {
		$query_result = $this->execute_query();
		$result = $this->db->fetch_assoc($query_result);
		return $result;
	}

	//execute query and get results as enumerated array
	public function fetch_array() {
		$query_result = $this->execute_query();
		$results = array();
		
		while ($row = $this->db->fetch_assoc($query_result)) {
			$results[] = $row;
		}

		return $results;
	}

	//retrieves number of rows
	public function rows_count(): int {
		$query_result = $this->execute_query();
		return $this->db->num_rows($query_result);
	}

	//retrieves query string
	public function query_string() {
		return $this->query;
	}
}

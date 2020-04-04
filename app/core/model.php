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
	private function execute_query() {
		$params = array();

		foreach ($this->params as $key => $value) {
			$params[] = $this->db->escape_string($value);
		}

		$this->db->execute_query($this->query, $params);
	}

	//generate sql query string
	public function select(string $column = '*') {
		$this->query = " SELECT $column ";
		return $this;
	}

	public function from(string $table) {
		$this->query = " FROM $table ";
		return $this;
	}

	public function where(string $column, string $value) {
		$this->query = " WHERE $column ? ";
		$this->params[] = $value;
		return $this;
	}

	public function order_by(string $column, string $direction) {
		$this->query = " ORDER BY $column $direction ";
		return $this;
	}

	public function limit(int $limit, int $offset = 0) {
		$this->query = " LIMIT $limit";

		if ($offset != 0) {
			$this->query .= ", $offset";
		}

		return $this;
	}

	public function set(string $column, string $value) {
		$this->query = " SET $column ? ";
		$this->params[] = $value;
		return $this;
	}
	//

	//execute sql insert query
	public function insert(string $table, array $data): int {
		$this->query = "INSERT INTO $table (";

		foreach($data as $key => $value) {
			$this->query .= "$key, ";
		}

		$this->query = rtrim($this->query, ',');
		$this->query .= ') VALUES (';

		foreach($data as $key => $value) {
			$this->query .= '?, ';
			$this->params[] = $value;
		}

		$this->query = rtrim($this->query, ', ');
		$this->query .= ')';

		$query_result = $this->execute_query();

		//return last insert id
		return $this->db->last_insert_id();
	}

	//execute sql update query
	public function update(string $table) {
		$this->query = 'UPDATE $table ';
		return $this;
	} 

	//execute query and get result
	public function fetch(): array {
		$query_result = $this->execute_query();
		$result = $this->db->fetch_assoc($query_result);
		return $result;
	}

	//execute query and get results as enumerated array
	public function fetch_array(): array {
		$query_result = $this->execute_query();
		$result_array = array();
		
		while ($row = $this->db->fetch_assoc($query_result)) {
			$result_array[] = $row;
		}

		return $result_array;
	}

	//retrieves number of rows
	public function rows_count(): int {
		$query_result = $this->execute_query();
		return $this->db->mysqli_num_rows($query_result);
	}
}

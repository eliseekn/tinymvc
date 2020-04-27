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
 * Model
 * 
 * Database management system
 */
class Model
{
	private $db;
	private $query;
	private $params = array();

	/**
	 * create new database instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->db = new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
	}

	/**
	 * generate SELECT query
	 *
	 * @param  mixed $columns
	 * @return void
	 */
	public function select(...$columns)
	{
		$this->query = 'SELECT ';

		foreach ($columns as $column) {
			$this->query .= "$column, ";
		}

		$this->query = rtrim($this->query, ', ');
		return $this;
	}

	/**
	 * generate SELECT AS query
	 *
	 * @param  string $columns
	 * @param  string $alias
	 * @return void
	 */
	public function select_as(string $column, string $alias)
	{
		$this->query = "SELECT $column AS $alias";
		return $this;
	}

	/**
	 * generate FROM query
	 *
	 * @param  string $table
	 * @return void
	 */
	public function from(string $table)
	{
		$this->query .= " FROM $table ";
		return $this;
	}

	/**
	 * generate WHERE query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  string $value
	 * @return void
	 */
	public function where(string $column, string $operator, string $value)
	{
		$this->query .= " WHERE $column $operator ? ";
		$this->params[] = $value;
		return $this;
	}

	/**
	 * generate AND query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  string $value
	 * @return void
	 */
	public function and(string $column, string $operator, string $value)
	{
		$this->query .= " AND $column $operator ? ";
		$this->params[] = $value;
		return $this;
	}

	/**
	 * generate OR query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  string $value
	 * @return void
	 */
	public function or(string $column, string $operator, string $value)
	{
		$this->query .= " OR $column $operator ? ";
		$this->params[] = $value;
		return $this;
	}

	/**
	 * generate ORDER BY query
	 *
	 * @param  string $column
	 * @param  string $direction
	 * @return void
	 */
	public function order_by(string $column, string $direction)
	{
		$this->query .= " ORDER BY $column $direction ";
		return $this;
	}

	/**
	 * generate LIKE query
	 *
	 * @param  string $column
	 * @param  string $value
	 * @return void
	 */
	public function like(string $column, string $value)
	{
		$this->query .= " WHERE $column LIKE '%$value%' ";
		return $this;
	}

	/**
	 * generate OR LIKE query
	 *
	 * @param  string $column
	 * @param  string $value
	 * @return void
	 */
	public function or_like(string $column, string $value)
	{
		$this->query .= " OR $column LIKE '%$value%' ";
		return $this;
	}

	/**
	 * generate LIMIT query
	 *
	 * @param  int $limit
	 * @param  int $offset
	 * @return void
	 */
	public function limit(int $limit, int $offset = 0)
	{
		$this->query .= " LIMIT $limit";

		if ($offset != 0) {
			$this->query .= ", $offset";
		}

		return $this;
	}

	/**
	 * generate INNER JOIN query
	 *
	 * @param  string $table
	 * @param  string $second_column
	 * @param  string $first_column
	 * @return void
	 */
	public function inner_join(string $table, string $second_column, string $first_column)
	{
		$this->query .= " INNER JOIN $table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate LEFT JOIN query
	 *
	 * @param  string $table
	 * @param  string $second_column
	 * @param  string $first_column
	 * @return void
	 */
	public function left_join(string $table, string $second_column, string $first_column)
	{
		$this->query .= " LEFT JOIN $table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate RIGHT JOIN query
	 *
	 * @param  string $table
	 * @param  string $second_column
	 * @param  string $first_column
	 * @return void
	 */
	public function right_join(string $table, string $second_column, string $first_column)
	{
		$this->query .= " RIGHT JOIN $table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate FULL JOIN query
	 *
	 * @param  string $table
	 * @param  string $second_column
	 * @param  string $first_column
	 * @return void
	 */
	public function full_join(string $table, string $second_column, string $first_column)
	{
		$this->query .= " FULL JOIN $table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate SET query
	 *
	 * @param  array $items
	 * @return void
	 */
	public function set(array $items)
	{
		$this->query .= " SET ";

		foreach ($items as $key => $value) {
			$this->query .= "$key = ?, ";
			$this->params[] = $value;
		}

		$this->query = rtrim($this->query, ', ');
		return $this;
	}

	/**
	 * execute safe sql query
	 *
	 * @return void
	 */
	public function execute_query()
	{
		$params = array_map(function ($value) {
			return $this->db->escape_string($value);
		}, array_values($this->params));

		$query_result = $this->db->execute_query($this->query, $params);
		$this->set_query_string('');

		return $query_result;
	}

	/**
	 * generate INSERT query
	 *
	 * @param  string $table
	 * @param  array $items
	 * @return void
	 */
	public function insert(string $table, array $items)
	{
		$this->query = "INSERT INTO $table (";

		foreach ($items as $key => $value) {
			$this->query .= "$key, ";
		}

		$this->query = rtrim($this->query, ', ');
		$this->query .= ') VALUES (';

		foreach ($items as $key => $value) {
			$this->query .= '?, ';
			$this->params[] = $value;
		}

		$this->query = rtrim($this->query, ', ');
		$this->query .= ')';

		return $this;
	}

	/**
	 * generate UPDATE query
	 *
	 * @param  string $table
	 * @return void
	 */
	public function update(string $table)
	{
		$this->query = "UPDATE $table";
		return $this;
	}

	/**
	 * generate DELETE query
	 *
	 * @return void
	 */
	public function delete()
	{
		$this->query = 'DELETE';
		return $this;
	}

	/**
	 * execute query and retrieves results
	 *
	 * @return mixed
	 */
	public function fetch()
	{
		$query_result = $this->execute_query();
		$result = $this->db->fetch_assoc($query_result);
		return $result;
	}

	/**
	 * execute query and retrieves results recursively
	 *
	 * @return array
	 */
	public function fetch_array(): array
	{
		$query_result = $this->execute_query();
		$results = array();

		while ($row = $this->db->fetch_assoc($query_result)) {
			$results[] = $row;
		}

		return $results;
	}

	/**
	 * retrieves rows count
	 *
	 * @return int
	 */
	public function rows_count(): int
	{
		$query_result = $this->execute_query();
		return $this->db->num_rows($query_result);
	}

	/**
	 * retrueves last inserted row id
	 *
	 * @return int
	 */
	public function insert_id(): int
	{
		return $this->db->last_insert_id();
	}

	/**
	 * returns query string
	 *
	 * @return string
	 */
	public function get_query_string(): string
	{
		return $this->query;
	}

	/**
	 * set query string
	 *
	 * @param  string $query
	 * @param  array $params
	 * @return void
	 */
	public function set_query_string(string $query, array $params = [])
	{
		$this->query = $query;
		$this->params = $params;
		return $this;
	}
}

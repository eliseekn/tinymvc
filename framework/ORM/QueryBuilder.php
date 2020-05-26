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

use PDOException;

/**
 * QueryBuilder
 * 
 * Database management system
 */
class QueryBuilder
{	
	/**
	 * database instance
	 *
	 * @var mixed
	 */
	private $db;
		
	/**
	 * sql query string
	 *
	 * @var string
	 */
	private $query;
		
	/**
	 * sql query arguments
	 *
	 * @var array
	 */
	private $args = [];

	/**
	 * get database connection instance
	 *
	 * @return void
	 */
	public function __construct()
	{
		//database connection instance
		$db = Database::getInstance();
		$this->db = $db->getConnection();
	}

	/**
	 * execute sql query
	 *
	 * @return mixed returns query result
	 */
	public function executeQuery()
	{
		try {
			$stmt = $this->db->prepare(trim($this->query));
			$stmt->execute($this->args);
		} catch (PDOException $e) {
			if (DISPLAY_ERRORS == true) {
				die($e->getMessage());
			}
		}

		//instantiates query and args values
		$this->setQuery('');

		//return query results
		return $stmt;
	}

	/**
	 * generate SELECT query
	 *
	 * @param  mixed $columns name of columns as enumerated string
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
	 * generate FROM query
	 *
	 * @param  string $table name of table
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
	 * @param  string $column column name
	 * @param  string $operator comparaison operator (<, = and >)
	 * @param  string $value element to be compared 
	 * @return void
	 */
	public function where(string $column, string $operator, string $value)
	{
		$this->query .= " WHERE $column $operator ? ";
		$this->args[] = $value;
		return $this;
	}

	/**
	 * generate AND query
	 *
	 * @param  string $column column name
	 * @param  string $operator comparaison operator (<, = and >)
	 * @param  string $value element to be compared 
	 * @return void
	 */
	public function and(string $column, string $operator, string $value)
	{
		$this->query .= " AND $column $operator ? ";
		$this->args[] = $value;
		return $this;
	}

	/**
	 * generate OR query
	 *
	 * @param  string $column column name
	 * @param  string $operator comparaison operator (<, = and >)
	 * @param  string $value element to be compared 
	 * @return void
	 */
	public function or(string $column, string $operator, string $value)
	{
		$this->query .= " OR $column $operator ? ";
		$this->args[] = $value;
		return $this;
	}

	/**
	 * generate ORDER BY query
	 *
	 * @param  string $column column name
	 * @param  string $direction order direction ASC or DESC
	 * @return void
	 */
	public function orderBy(string $column, string $direction)
	{
		$this->query .= " ORDER BY $column " . strtoupper($direction);
		return $this;
	}

	/**
	 * generate LIKE query
	 *
	 * @param  string $column column name
	 * @param  string $value element to be compared
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
	 * @param  string $column column name
	 * @param  string $value element to be compared
	 * @return void
	 */
	public function orLike(string $column, string $value)
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
	public function limit(int $limit, ?int $offset = null)
	{
		$this->query .= " LIMIT $limit";

		if (!is_null($offset)) {
			$this->query .= ", $offset";
		}

		return $this;
	}

	/**
	 * generate INNER JOIN query
	 *
	 * @param  string $table table name
	 * @param  string $second_column second column name
	 * @param  string $first_column first column name
	 * @return void
	 */
	public function innerJoin(string $table, string $second_column, string $first_column)
	{
		$this->query .= " INNER JOIN $table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate LEFT JOIN query
	 *
	 * @param  string $table table name
	 * @param  string $second_column second column name
	 * @param  string $first_column first column name
	 * @return void
	 */
	public function leftJoin(string $table, string $second_column, string $first_column)
	{
		$this->query .= " LEFT JOIN $table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate RIGHT JOIN query
	 *
	 * @param  string $table table name
	 * @param  string $second_column second column name
	 * @param  string $first_column first column name
	 * @return void
	 */
	public function rightJoin(string $table, string $second_column, string $first_column)
	{
		$this->query .= " RIGHT JOIN $table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate FULL JOIN query
	 *
	 * @param  string $table table name
	 * @param  string $second_column second column name
	 * @param  string $first_column first column name
	 * @return void
	 */
	public function fullJoin(string $table, string $second_column, string $first_column)
	{
		$this->query .= " FULL JOIN $table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate SET query
	 *
	 * @param  array $items columns to update
	 * @return void
	 */
	public function set(array $items)
	{
		$this->query .= " SET ";

		foreach ($items as $key => $value) {
			$this->query .= "$key = ?, ";
			$this->args[] = $value;
		}

		$this->query = rtrim($this->query, ', ');
		return $this;
	}

	/**
	 * generate INSERT query
	 *
	 * @param  string $table table name
	 * @param  array $items items to insert in columns
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
			$this->args[] = $value;
		}

		$this->query = rtrim($this->query, ', ');
		$this->query .= ')';

		return $this;
	}

	/**
	 * generate UPDATE query
	 *
	 * @param  string $table table name
	 * @return void
	 */
	public function update(string $table)
	{
		$this->query = "UPDATE $table";
		return $this;
	}

	/**
	 * generate DELETE FROM query
	 * 
	 * @param  string $table table name
	 * @return void
	 */
	public function deleteFrom(string $table)
	{
		$this->query = "DELETE FROM $table";
		return $this;
	}

	/**
	 * execute query and retrieves one row result
	 *
	 * @return array
	 */
	public function fetchSingle(): array
	{
		$query_result = $this->executeQuery();
		$data = $query_result->fetch();
		return is_array($data) ? $data : [];
	}

	/**
	 * execute query and retrieves all results
	 *
	 * @return array
	 */
	public function fetchAll(): array
	{
		$query_result = $this->executeQuery();
		$data = $query_result->fetchAll();
		return is_array($data) ? $data : [];
	}

	/**
	 * retrieves rows count
	 *
	 * @return int
	 */
	public function rowsCount(): int
	{
		$query_result = $this->executeQuery();
		return $query_result->rowCount();
	}

	/**
	 * returns query string
	 *
	 * @return string
	 */
	public function getQuery(): string
	{
		return $this->query;
	}

	/**
	 * set custom query string
	 *
	 * @param  string $query query string
	 * @param  array $args query arguments
	 * @return void
	 */
	public function setQuery(string $query, array $args = [])
	{
		$this->query = $query;
		$this->args = $args;
		return $this;
	}
}

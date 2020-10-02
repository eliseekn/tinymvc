<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

use PDOException;

/**
 * Database management system
 */
class Query
{	
	/**
	 * database instance
	 *
	 * @var mixed
	 */
	protected static $db;
		
	/**
	 * sql query string
	 *
	 * @var string
	 */
	protected static $query = '';
		
	/**
	 * sql query arguments
	 *
	 * @var array
	 */
	protected static $args = [];

	/**
	 * get database connection instance
	 *
	 * @return \Framework\ORM\Query
	 */
	public static function DB(): self
	{
		//database connection instance
		$db = Database::getInstance();
		self::$db = $db->getConnection();

		return new self();
	}

	/**
	 * execute sql query
	 *
	 * @return mixed|null returns query result
	 */
	public function executeQuery()
	{
		$stmt= null;

		try {
			$stmt = self::$db->prepare(trim(self::$query));
			$stmt->execute(self::$args);
		} catch (PDOException $e) {
			if (config('errors.display') == true) {
				die($e->getMessage());
			}
		}

		//instantiates query and args values
		$this->setQuery('');

		//return query results
		return $stmt;
	}

	/**
	 * returns query string and arguments
	 *
	 * @return array
	 */
	public function getQuery(): array
	{
		return [
			self::$query,
			self::$args
		];
	}

	/**
	 * set custom query string
	 *
	 * @param  string $query query string
	 * @param  array $args query arguments
	 * @return \Framework\ORM\Query
	 */
	public function setQuery(string $query, array $args = []): self
	{
		self::$query = $query;
		self::$args = $args;
		return $this;
	}

	/**
	 * generate SELECT query
	 *
	 * @param  string $columns name of columns as enumerated string
	 * @return \Framework\ORM\Query
	 */
	public function select(string ...$columns): self
	{
		self::$query = 'SELECT ';

		foreach ($columns as $column) {
			self::$query .= "$column, ";
		}

		self::$query = rtrim(self::$query, ', ');
		return $this;
	}

	/**
	 * generate FROM query
	 *
	 * @param  string $table
	 * @return \Framework\ORM\Query
	 */
	public function from(string $table): self
	{
		self::$query .= " FROM " . config('database.table_prefix') . "$table ";
		return $this;
	}

	/**
	 * generate WHERE query
	 *
	 * @param  string $column
	 * @param  string $operator (<, =, >, IN or NOT IN)
	 * @param  mixed $value
	 * @return \Framework\ORM\Query
	 */
	public function where(string $column, string $operator, $value): self
	{
		self::$query .= " WHERE $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate WHERE = query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return mixed
	 */
	public function whereEquals(string $column, $value)
	{
		return $this->where($column, '=', $value);
	}

	/**
	 * generate WHERE > query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return mixed
	 */
	public function whereGreater(string $column, $value)
	{
		return $this->where($column, '>', $value);
	}

	/**
	 * generate WHERE < query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return mixed
	 */
	public function whereLower(string $column, $value)
	{
		return $this->where($column, '<', $value);
	}

	/**
	 * generate WHERE NOT IN query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return mixed
	 */
	public function whereNotIn(string $column, $value)
	{
		return $this->where($column, 'NOT IN', $value);
	}

	/**
	 * generate WHERE IN query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return mixed
	 */
	public function whereIn(string $column, $value)
	{
		return $this->where($column, 'IN', $value);
	}

	/**
	 * generate HAVING query
	 *
	 * @param  string $column
	 * @param  string $operator (<, =, >, IN or NOT IN)
	 * @param  mixed $value
	 * @return \Framework\ORM\Query
	 */
	public function having(string $column, string $operator, $value): self
	{
		self::$query .= " HAVING $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate AND query
	 *
	 * @param  string $column
	 * @param  string $operator (<, =, >, IN or NOT IN)
	 * @param  mixed $value
	 * @return \Framework\ORM\Query
	 */
	public function and(string $column, string $operator, $value): self
	{
		self::$query .= " AND $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate OR query
	 *
	 * @param  string $column
	 * @param  string $operator (<, =, >, IN or NOT IN)
	 * @param  mixed $value
	 * @return \Framework\ORM\Query
	 */
	public function or(string $column, string $operator, $value): self
	{
		self::$query .= " OR $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate ORDER BY query
	 *
	 * @param  string $column
	 * @param  string $direction (ASC or DESC)
	 * @return \Framework\ORM\Query
	 */
	public function orderBy(string $column, string $direction): self
	{
		self::$query .= " ORDER BY $column " . strtoupper($direction);
		return $this;
	}

	/**
	 * generate GROUP BY query
	 *
	 * @param  string $column
	 * @return \Framework\ORM\Query
	 */
	public function groupBy(string $column): self
	{
		self::$query .= " GROUP BY $column ";
		return $this;
	}

	/**
	 * generate LIKE query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Query
	 */
	public function like(string $column, $value): self
	{
		self::$query .= " WHERE $column LIKE '%?%' ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate OR LIKE query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Query
	 */
	public function orLike(string $column, $value): self
	{
		self::$query .= " OR $column LIKE '%?%' ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate LIMIT query
	 *
	 * @param  int $limit
	 * @param  int $offset
	 * @return \Framework\ORM\Query
	 */
	public function limit(int $limit, ?int $offset = null): self
	{
		self::$query .= " LIMIT $limit";

		if (!is_null($offset)) {
			self::$query .= ", $offset";
		}

		return $this;
	}

	/**
	 * generate INNER JOIN query
	 *
	 * @param  string $table
	 * @param  string $second_column
	 * @param  string $first_column
	 * @return \Framework\ORM\Query
	 */
	public function innerJoin(string $table, string $second_column, string $first_column): self
	{
		self::$query .= " INNER JOIN " . config('database.table_prefix') . "$table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate LEFT JOIN query
	 *
	 * @param  string $table
	 * @param  string $second_column
	 * @param  string $first_column
	 * @return \Framework\ORM\Query
	 */
	public function leftJoin(string $table, string $second_column, string $first_column): self
	{
		self::$query .= " LEFT JOIN " . config('database.table_prefix') . "$table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate RIGHT JOIN query
	 *
	 * @param  string $table
	 * @param  string $second_column
	 * @param  string $first_column
	 * @return \Framework\ORM\Query
	 */
	public function rightJoin(string $table, string $second_column, string $first_column): self
	{
		self::$query .= " RIGHT JOIN " . config('database.table_prefix') . "$table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate FULL JOIN query
	 *
	 * @param  string $table
	 * @param  string $second_column
	 * @param  string $first_column
	 * @return \Framework\ORM\Query
	 */
	public function fullJoin(string $table, string $second_column, string $first_column): self
	{
		self::$query .= " FULL JOIN " . config('database.table_prefix') . "$table ON $first_column = $second_column";
		return $this;
	}

	/**
	 * generate SET query
	 *
	 * @param  array $items
	 * @return \Framework\ORM\Query
	 */
	public function set(array $items): self
	{
		self::$query .= " SET ";

		foreach ($items as $key => $value) {
			self::$query .= "$key = ?, ";
			self::$args[] = $value;
		}

		self::$query = rtrim(self::$query, ', ');
		return $this;
	}

	/**
	 * generate INSERT query
	 *
	 * @param  string $table
	 * @param  array $items
	 * @return \Framework\ORM\Query
	 */
	public function insert(string $table, array $items): self
	{
		self::$query = "INSERT INTO " . config('database.table_prefix') . "$table (";

		foreach ($items as $key => $value) {
			self::$query .= "$key, ";
		}

		self::$query = rtrim(self::$query, ', ');
		self::$query .= ') VALUES (';

		foreach ($items as $key => $value) {
			self::$query .= '?, ';
			self::$args[] = $value;
		}

		self::$query = rtrim(self::$query, ', ');
		self::$query .= ')';

		return $this;
	}

	/**
	 * generate UPDATE query
	 *
	 * @param  string $table
	 * @return \Framework\ORM\Query
	 */
	public function update(string $table): self
	{
		self::$query = "UPDATE " . config('database.table_prefix') . "$table";
		return $this;
	}

	/**
	 * generate DELETE FROM query
	 * 
	 * @param  string $table
	 * @return \Framework\ORM\Query
	 */
	public function deleteFrom(string $table): self
	{
		self::$query = "DELETE FROM " . config('database.table_prefix') . "$table";
		return $this;
	}

	/**
	 * execute query and retrieves one row result
	 *
	 * @return mixed
	 */
	public function fetchSingle()
	{
		$query_result = $this->executeQuery();
		return $query_result->fetch();
	}

	/**
	 * execute query and retrieves all results
	 *
	 * @return mixed
	 */
	public function fetchAll()
	{
		$query_result = $this->executeQuery();
		return $query_result->fetchAll();
	}

	/**
	 * retrieves rows count
	 *
	 * @return int
	 */
	public function count(): int
	{
		$query_result = $this->executeQuery();
		return $query_result->rowCount();
	}

	/**
	 * retrieves last inserted id
	 *
	 * @return int
	 */
	public function lastInsertedId(): int
	{
		return self::$db->lastInsertId();
	}
}

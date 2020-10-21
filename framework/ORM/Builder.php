<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

/**
 * Database query builder
 */
class Builder
{
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
     * generate CREATE TABLE query 
     *
     * @param  string $name
     * @return \Framework\ORM\Builder
     */
    public static function table(string $name): self
    {
        self::$query = "CREATE TABLE " . config('database.table_prefix') . "$name (";
        return new self();
	}

	/**
	 * generate SELECT query
	 *
	 * @param  string $columns
	 * @return \Framework\ORM\Builder
	 */
	public static function select(string ...$columns): self
	{
		self::$query = 'SELECT ';

		foreach ($columns as $column) {
			self::$query .= "$column, ";
		}

		self::$query = rtrim(self::$query, ', ');
		return new self();
	}

	/**
	 * generate INSERT query
	 *
	 * @param  string $table
	 * @param  array $items
	 * @return \Framework\ORM\Builder
	 */
	public static function insert(string $table, array $items): self
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

		return new self();
	}

	/**
	 * generate UPDATE query
	 *
	 * @param  string $table
	 * @return \Framework\ORM\Builder
	 */
	public static function update(string $table): self
	{
		self::$query = "UPDATE " . config('database.table_prefix') . "$table";
		return new self();
	}

	/**
	 * generate DELETE FROM query
	 * 
	 * @param  string $table
	 * @return \Framework\ORM\Builder
	 */
	public static function delete(string $table): self
	{
		self::$query = "DELETE FROM " . config('database.table_prefix') . "$table";
		return new self();
	}
	
	/**
	 * generate DROP TABLE query
	 *
	 * @param  string $table
	 * @return \Framework\ORM\Builder
	 */
	public static function drop(string $table): self
	{
		self::$query = "DROP TABLE IF EXISTS " . config('database.table_prefix') . "$table";
		return new self();
	}
	
	/**
	 * generate DROP FOREIGN KEY query
	 *
	 * @param  string $table
	 * @param  string $key foreign key name
	 * @return \Framework\ORM\Builder
	 */
	public static function dropForeign(string $table, string $key): self
	{
		self::$query = "ALTER TABLE " . config('database.table_prefix') . "$table DROP FOREIGN KEY $key";
		return new self();
	}
		
	/**
	 * column
	 *
	 * @param  string $name
	 * @param  string $type
	 * @return self
	 */
	public function column(string $name, string $type): self
	{
		self::$query .= "$name $type NOT NULL, ";
		return $this;
	}
    
    /**
     * add primary key and auto increment attributes
     *
     * @return \Framework\ORM\Builder
     */
    public function primaryKey(): self
    {
        self::$query = rtrim(self::$query, ', ');
        self::$query .= ' AUTO_INCREMENT PRIMARY KEY, ';
        return $this;
    }
    
    /**
     * add null attribute
     * 
     * @return \Framework\ORM\Builder
     */
    public function null(): self
    {
        self::$query = str_replace('NOT NULL, ', 'NULL, ', self::$query);
        return $this;
    }

    /**
     * add unique attribute
     *
     * @return \Framework\ORM\Builder
     */
    public function unique(): self
    {
        self::$query = rtrim(self::$query, ', ');
        self::$query .= ' UNIQUE, ';
        return $this;
    }

    /**
     * add default attribute
     *
     * @param  mixed $default
     * @return \Framework\ORM\Builder
     */
    public function default($default): self
    {
        self::$query = rtrim(self::$query, ', ');
        self::$query .= " DEFAULT '$default', ";
        return $this;
	}
		
	/**
	 * add foreign key
	 *
	 * @param  string $name
	 * @param  string $column
	 * @return \Framework\ORM\Builder
	 */
	public function foreign(string $name, string $column): self
	{
		self::$query .= " CONSTRAINT $name FOREIGN KEY ($column)";
        return $this;
	}
	
	/**
	 * add references
	 *
	 * @param  string $table
	 * @param  string $column
	 * @return \Framework\ORM\Builder
	 */
	public function references(string $table, string $column): self
	{
		self::$query .= " REFERENCES $table($column)";
        return $this;
	}
	
	/**
	 * onUpdate
	 *
	 * @return \Framework\ORM\Builder
	 */
	public function onUpdate(): self
	{
		self::$query .= " ON UPDATE";
        return $this;
	}
	
	/**
	 * onDelete
	 *
	 * @return \Framework\ORM\Builder
	 */
	public function onDelete(): self
	{
		self::$query .= " ON DELETE";
        return $this;
	}
	
	/**
	 * cascade
	 *
	 * @return \Framework\ORM\Builder
	 */
	public function cascade(): self
	{
		self::$query .= " CASCADE";
        return $this;
	}
	
	/**
	 * setNull
	 *
	 * @return \Framework\ORM\Builder
	 */
	public function setNull(): self
	{
		self::$query .= " SET NULL";
        return $this;
	}
    
    /**
     * create new table
     *
     * @return \Framework\ORM\Builder
     */
    public function create(): self
    {
		self::$query .= "created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
			updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)";
		return $this;
    }

	/**
	 * generate FROM query
	 *
	 * @param  string $table
	 * @return \Framework\ORM\Builder
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
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Builder
	 */
	public function where(string $column, string $operator, $value): self
	{
		self::$query .= " WHERE $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate HAVING query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Builder
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
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Builder
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
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Builder
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
	 * @return \Framework\ORM\Builder
	 */
	public function orderBy(string $column, string $direction): self
	{
		self::$query .= " ORDER BY $column " . strtoupper($direction);
		return $this;
	}

	/**
	 * generate GROUP BY query
	 *
	 * @param  string of string $columns
	 * @return \Framework\ORM\Builder
	 */
	public function groupBy(string ...$columns): self
	{
		self::$query .= ' GROUP BY ';

		foreach ($columns as $column) {
			self::$query .= "$column, ";
		}

		self::$query = rtrim(self::$query, ', ');
		return $this;
	}

	/**
	 * generate LIKE query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Builder
	 */
	public function like(string $column, $value): self
	{
		self::$query .= " WHERE $column LIKE '%$value%' ";
		return $this;
	}

	/**
	 * generate NOT LIKE query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Builder
	 */
	public function notLike(string $column, $value): self
	{
		self::$query .= " WHERE $column NOT LIKE '%$value%' ";
		return $this;
	}

	/**
	 * generate OR LIKE query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Builder
	 */
	public function orLike(string $column, $value): self
	{
		self::$query .= " OR $column LIKE '%$value%' ";
		return $this;
	}

	/**
	 * generate OR NOT LIKE query
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Builder
	 */
	public function orNotLike(string $column, $value): self
	{
		self::$query .= " OR $column NOT LIKE '%$value%' ";
		return $this;
	}

	/**
	 * generate LIMIT query
	 *
	 * @param  int $limit
	 * @param  int $offset
	 * @return \Framework\ORM\Builder
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
	 * @return \Framework\ORM\Builder
	 */
	public function join(string $table, string $second_column, string $first_column): self
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
	 * @return \Framework\ORM\Builder
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
	 * @return \Framework\ORM\Builder
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
	 * @return \Framework\ORM\Builder
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
	 * @return \Framework\ORM\Builder
	 */
	public function set(array $items): self
	{
		self::$query .= " SET ";

		//update last modifed timestamp
		$items = array_merge($items, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);

		foreach ($items as $key => $value) {
			self::$query .= "$key = ?, ";
			self::$args[] = $value;
		}

		self::$query = rtrim(self::$query, ', ');
		return $this;
	}

	/**
	 * returns query string and arguments
	 *
	 * @return array
	 */
	public function get(): array
	{
		return [self::$query, self::$args];
	}

	/**
	 * set query string and arguments
	 *
	 * @return \Framework\ORM\Builder
	 */
	public static function query(string $query, array $args = []): self
	{
		self::$query = $query;
		self::$args = $args;

		return new self();
	}
	
	/**
	 * execute query with arguments
	 *
	 * @return \PDOStatement
	 */
	public function execute(): \PDOStatement
	{
		$stmt = Database::getInstance()->executeQuery(self::$query, self::$args);
		self::query('');
		return $stmt;
	}
}

<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Database;

use Carbon\Carbon;

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
     * @return \Framework\Database\Builder
     */
    public static function table(string $name): self
    {
        self::$query = "CREATE TABLE " . config('db.table_prefix') . "$name (";
        return new self();
	}

	/**
	 * generate SELECT query
	 *
	 * @param  string $columns
	 * @return \Framework\Database\Builder
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
     * generate SELECT with raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\Builder
     */
    public static function selectRaw(string $query, array $args = []): self
    {
        self::$query = 'SELECT ' . $query;
        self::$args = array_merge(self::$args, $args);

        return new self();
    }

	/**
	 * generate INSERT query
	 *
	 * @param  string $table
	 * @param  array $items
	 * @return \Framework\Database\Builder
	 */
	public static function insert(string $table, array $items): self
	{
		self::$query = "INSERT INTO " . config('db.table_prefix') . "$table (";

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
	 * @return \Framework\Database\Builder
	 */
	public static function update(string $table): self
	{
		self::$query = "UPDATE " . config('db.table_prefix') . "$table";
		return new self();
	}

	/**
	 * generate DELETE FROM query
	 * 
	 * @param  string $table
	 * @return \Framework\Database\Builder
	 */
	public static function delete(string $table): self
	{
		self::$query = "DELETE FROM " . config('db.table_prefix') . "$table";
		return new self();
	}
	
	/**
	 * generate DROP TABLE query
	 *
	 * @param  string $table
	 * @return \Framework\Database\Builder
	 */
	public static function drop(string $table): self
	{
		self::$query = "DROP TABLE IF EXISTS " . config('db.table_prefix') . "$table";
		return new self();
	}
	
	/**
	 * generate DROP FOREIGN KEY query
	 *
	 * @param  string $table
	 * @param  string $key foreign key name
	 * @return \Framework\Database\Builder
	 */
	public static function dropForeign(string $table, string $key): self
	{
		self::$query = "ALTER TABLE " . config('db.table_prefix') . "$table DROP FOREIGN KEY $key";
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
     * @return \Framework\Database\Builder
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
     * @return \Framework\Database\Builder
     */
    public function null(): self
    {
        self::$query = str_replace('NOT NULL, ', 'NULL, ', self::$query);
        return $this;
    }

    /**
     * add unique attribute
     *
     * @return \Framework\Database\Builder
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
     * @return \Framework\Database\Builder
     */
    public function default($default): self
    {
        self::$query = rtrim(self::$query, ', ');
        self::$query .= " DEFAULT '$default', ";
        return $this;
	}
		
	/**
	 * add foreign key constraint
	 *
	 * @param  string $name
	 * @param  string $column
	 * @return \Framework\Database\Builder
	 */
	public function foreignKey(string $name, string $column): self
	{
		self::$query .= " CONSTRAINT $name FOREIGN KEY ($column)";
        return $this;
	}
	
	/**
	 * add references
	 *
	 * @param  string $table
	 * @param  string $column
	 * @return \Framework\Database\Builder
	 */
	public function references(string $table, string $column): self
	{
		self::$query .= " REFERENCES " . config('db.table_prefix') . "$table($column)";
        return $this;
	}
	
	/**
	 * onUpdate
	 *
	 * @return \Framework\Database\Builder
	 */
	public function onUpdate(): self
	{
        self::$query = rtrim(self::$query, ', ');
		self::$query .= " ON UPDATE";
        return $this;
	}
	
	/**
	 * onDelete
	 *
	 * @return \Framework\Database\Builder
	 */
	public function onDelete(): self
	{
        self::$query = rtrim(self::$query, ', ');
		self::$query .= " ON DELETE";
        return $this;
	}
	
	/**
	 * cascade
	 *
	 * @return \Framework\Database\Builder
	 */
	public function cascade(): self
	{
		self::$query .= " CASCADE, ";
        return $this;
	}
	
	/**
	 * setNull
	 *
	 * @return \Framework\Database\Builder
	 */
	public function setNull(): self
	{
		self::$query .= " SET NULL, ";
        return $this;
	}
    
    /**
     * create new table
     *
     * @return \Framework\Database\Builder
     */
    public function create(): self
    {
        if (config('db.timestamps') === true) {
            self::$query .= "created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
			    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)";
        }

        self::$query .= " ENGINE='" . config('db.storage_engine') . "'";
		return $this;
    }

	/**
	 * generate FROM query
	 *
	 * @param  string $table
	 * @return \Framework\Database\Builder
	 */
	public function from(string $table): self
	{
		self::$query .= " FROM " . config('db.table_prefix') . "$table ";
		return $this;
	}

	/**
	 * generate WHERE query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Builder
	 */
	public function where(string $column, string $operator, $value): self
	{
		self::$query .= " WHERE $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}
        
    /**
     * generate WHERE with raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\Builder
     */
    public function whereRaw(string $query, array $args = []): self
    {
        self::$query .= ' WHERE ' . $query;
        self::$args = array_merge(self::$args, $args);
        return $this;
    }

	/**
	 * generate WHERE NOT query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Builder
	 */
	public function whereNot(string $column, string $operator, $value): self
	{
		self::$query .= " WHERE NOT $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate WHERE query
	 *
	 * @param  string $column
	 * @return \Framework\Database\Builder
	 */
	public function whereColumn(string $column): self
	{
		self::$query .= " WHERE $column ";
		return $this;
	}

	/**
	 * generate OR query
	 *
	 * @param  string $column
	 * @return \Framework\Database\Builder
	 */
	public function orColumn(string $column): self
	{
		self::$query .= " OR $column ";
		return $this;
	}

	/**
	 * generate AND query
	 *
	 * @param  string $column
	 * @return \Framework\Database\Builder
	 */
	public function andColumn(string $column): self
	{
		self::$query .= " AND $column ";
		return $this;
	}

	/**
	 * generate IS NULL query
	 *
	 * @return \Framework\Database\Builder
	 */
	public function isNull(): self
	{
		self::$query .= ' IS NULL ';
		return $this;
	}

	/**
	 * generate IS NOT NULL query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Builder
	 */
	public function isNotNull(): self
	{
		self::$query .= ' IS NOT NULL ';
		return $this;
	}

	/**
	 * generate IN query
	 *
	 * @param  string $column
	 * @return \Framework\Database\Builder
	 */
	public function in(array $values): self
	{
		self::$query .= ' IN (' . implode(',', $values) . ') ';
		return $this;
    }
    
	/**
	 * generate NOT IN query
	 *
	 * @param  string $column
	 * @return \Framework\Database\Builder
	 */
	public function notIn(array $values): self
	{
		self::$query .= ' NOT IN (' . implode(',', $values) . ') ';
		return $this;
    }

	/**
	 * generate BETWEEN query
	 *
	 * @param  mixed $start
	 * @param  mixed $end
	 * @return \Framework\Database\Builder
	 */
	public function between($start, $end): self
	{
		self::$query .= " BETWEEN $start AND $end ";
		return $this;
    }
    
	/**
	 * generate NOT BETWEEN query
	 *
	 * @param  mixed $start
	 * @param  mixed $end
	 * @return \Framework\Database\Builder
	 */
	public function notBetween($start, $end): self
	{
		self::$query .= " NOT BETWEEN $start AND $end ";
		return $this;
    }

	/**
	 * generate LIKE query
	 *
	 * @param  mixed $value
	 * @return \Framework\Database\Builder
	 */
	public function like($value): self
	{
		self::$query .= " LIKE '%$value%' ";
		return $this;
	}

	/**
	 * generate NOT LIKE query
	 *
	 * @param  mixed $value
	 * @return \Framework\Database\Builder
	 */
	public function notLike($value): self
	{
		self::$query .= " NOT LIKE '%$value%' ";
		return $this;
	}

	/**
	 * generate AND query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Builder
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
	 * @return \Framework\Database\Builder
	 */
	public function or(string $column, string $operator, $value): self
	{
		self::$query .= " OR $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * generate HAVING query
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Builder
	 */
	public function having(string $column, string $operator, $value): self
	{
		self::$query .= " HAVING $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}
        
    /**
     * generate HAVING with raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\Builder
     */
    public function havingRaw(string $query, array $args = []): self
    {
        self::$query .= ' HAVING ' . $query;
        self::$args = array_merge(self::$args, $args);
        return $this;
    }

	/**
	 * generate ORDER BY query
	 *
	 * @param  string $column
	 * @param  string $direction (ASC or DESC)
	 * @return \Framework\Database\Builder
	 */
	public function orderBy(string $column, string $direction): self
	{
		self::$query .= " ORDER BY $column " . strtoupper($direction);
		return $this;
	}

	/**
	 * generate GROUP BY query
	 *
	 * @param  string[] $columns
	 * @return \Framework\Database\Builder
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
	 * generate LIMIT query
	 *
	 * @param  int $limit
	 * @param  int $offset
	 * @return \Framework\Database\Builder
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
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\Builder
	 */
	public function innerJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " INNER JOIN " . config('db.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * generate LEFT JOIN query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\Builder
	 */
	public function leftJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " LEFT JOIN " . config('db.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * generate RIGHT JOIN query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\Builder
	 */
	public function rightJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " RIGHT JOIN " . config('db.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * generate FULL JOIN query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\Builder
	 */
	public function fullJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " FULL JOIN " . config('db.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * generate FULL OUTER JOIN query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\Builder
	 */
	public function outerJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " FULL OUTER JOIN " . config('db.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * generate SET query
	 *
	 * @param  array $items
	 * @return \Framework\Database\Builder
	 */
	public function set(array $items): self
	{
		self::$query .= " SET ";

		//update last modifed timestamp
		if (config('db.timestamps') === true) {
            $items = array_merge($items, [
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }

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
	public function toSQL(): array
	{
		return [self::$query, self::$args];
	}

	/**
	 * set query string and arguments
	 *
     * @param  string $query
     * @param  array $args
	 * @return \Framework\Database\Builder
	 */
	public static function setQuery(string $query, array $args = []): self
	{
		self::$query = $query;
		self::$args = $args;

		return new self();
    }
    
    /**
     * add custom query string with arguments
     *
     * @param  mixed $query
     * @param  mixed $args
     * @return \Framework\Database\Builder
     */
    public function rawQuery(string $query, array $args = []): self
    {
        self::$query .= $query;
        self::$args = array_merge(self::$args, $args);
        
        return $this;
    }
    
    /**
     * generate sub query
     *
     * @param  mixed $function
     * @return \Framework\Database\Builder
     */
    public function subQuery(callable $function): self
    {
        call_user_func_array($function, [$this]);
        return $this;
    }
	
	/**
	 * execute query with arguments
	 *
	 * @return \PDOStatement
	 */
	public function execute(): \PDOStatement
	{
        $stmt = DBConnection::getInstance()->executeStatement(self::$query, self::$args);
		self::setQuery('');
		return $stmt;
	}
}
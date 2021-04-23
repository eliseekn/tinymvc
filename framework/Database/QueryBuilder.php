<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Database;

use Carbon\Carbon;

/**
 * Database query builder
 */
class QueryBuilder
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
     * name of table
     * 
     * @var string $table
     */
    protected static $table;
    
    /**
     * set table name
     *
     * @param  string $table
     * @return  \Framework\Database\QueryBuilder
     */
    public static function table(string $table): self
    {
        static::$table = config('database.table_prefix')  . $table;
        return new self();
    }

    /**
     * create table query
     *
     * @param  string $name
     * @return \Framework\Database\QueryBuilder
     */
    public static function createTable(string $name): self
    {
        self::$query = "CREATE TABLE " . config('database.table_prefix') . "$name (";
        return new self();
	}
	
	/**
	 * drop table query
	 *
	 * @param  string $table
	 * @return \Framework\Database\QueryBuilder
	 */
	public static function drop(string $table): self
	{
		self::$query = "DROP TABLE IF EXISTS " . config('database.table_prefix') . "$table";
		return new self();
	}
    
    /**
     * alter table query
     *
     * @param  string $table
     * @return \Framework\Database\QueryBuilder
     */
    public static function alter(string $table): self
    {
        self::$query = "ALTER TABLE " . config('database.table_prefix') . $table;
		return new self();
    }
	
	/**
	 * drop foreign key table query
	 *
	 * @param  string $table
	 * @param  string $key foreign key name
	 * @return \Framework\Database\QueryBuilder
	 */
	public static function dropForeign(string $table, string $key): self
	{
        self::alter($table);
        self::$query .= " DROP FOREIGN KEY $key";
		return new self();
	}
    
    /**
     * add column query
     *
     * @param  string $table
     * @return \Framework\Database\QueryBuilder
     */
    public static function addColumn(string $table): self
    {
        self::alter($table);
        self::$query .= " ADD COLUMN ";
        return new self();
    }
    
    /**
     * rename column query
     *
     * @param  string $table
     * @param  string $old
     * @param  string $new
     * @return \Framework\Database\QueryBuilder
     */
    public static function renameColumn(string $table, string $old, string $new): self
    {
        self::alter($table);
        self::$query .= " RENAME COLUMN $old TO $new";
        return new self();
    }
    
    /**
     * change column query
     *
     * @param  string $table
     * @param  string $column
     * @return \Framework\Database\QueryBuilder
     */
    public static function updateColumn(string $table, string $column): self
    {
        self::alter($table);
        self::$query .= " CHANGE $column ";
        return new self();
    }
    
    /**
     * delete column query
     *
     * @param  string $table
     * @param  string $column
     * @return \Framework\Database\QueryBuilder
     */
    public static function deleteColumn(string $table, string $column): self
    {
        self::alter($table);
        self::$query .= " DROP COLUMN $column";
        return new self();
    }
    
    /**
     * check if table exists
     *
     * @param  mixed $table
     * @return bool
     */
    public static function tableExists(string $table): bool
    {
        return self::setQuery('SELECT * FROM information_schema.tables WHERE table_schema = "' . config('database.database') .'" 
            AND table_name = "' . $table . '" LIMIT 1')->exists();
    }
    
    /**
     * check if database exists
     *
     * @param  mixed $db
     * @return bool
     */
    public static function schemaExists(string $db): bool
    {
        return self::setQuery('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . $db .'"')->exists();
    }

	/**
	 * select query
	 *
	 * @param  string $columns
	 * @return \Framework\Database\QueryBuilder
	 */
	public function select(string ...$columns): self
	{
		self::$query = 'SELECT ';

		foreach ($columns as $column) {
			self::$query .= "$column, ";
		}

		self::$query = rtrim(self::$query, ', ');
        self::$query .= ' FROM ' . static::$table;

		return new self();
	}
        
    /**
     * select raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\QueryBuilder
     */
    public function selectRaw(string $query, array $args = []): self
    {
        self::$query = 'SELECT ' . $query;
        self::$args = array_merge(self::$args, $args);
        self::$query .= ' FROM ' . static::$table;

        return new self();
    }

	/**
	 * insert query
	 *
	 * @param  array $items
	 * @return \Framework\Database\QueryBuilder
	 */
	public function insert(array $items): self
	{
		self::$query = "INSERT INTO " . static::$table . " (";

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
	 * update query
	 *
     * @param  array $items
	 * @return \Framework\Database\QueryBuilder
	 */
	public function update(array $items): self
	{
		self::$query = "UPDATE " . static::$table . " SET ";

		//update last modifed timestamp
		if (config('database.timestamps')) {
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
	 * delete query
	 * 
	 * @return \Framework\Database\QueryBuilder
	 */
	public function delete(): self
	{
		self::$query = "DELETE FROM " . static::$table;
		return new self();
	}
    
    /**
     * add after attribute
     *
     * @param  string $column
     * @return \Framework\Database\QueryBuilder
     */
    public function after(string $column): self
    {
        self::$query .= " AFTER $column";
        return $this;
    }
    
    /**
     * add first attribute
     *
     * @return \Framework\Database\QueryBuilder
     */
    public function first(): self
    {
        self::$query .= " FIRST";
        return $this;
    }
		
	/**
	 * set column attribute
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
     * @return \Framework\Database\QueryBuilder
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
     * @return \Framework\Database\QueryBuilder
     */
    public function null(): self
    {
        self::$query = str_replace('NOT NULL, ', 'NULL, ', self::$query);
        return $this;
    }

    /**
     * add unique attribute
     *
     * @return \Framework\Database\QueryBuilder
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
     * @return \Framework\Database\QueryBuilder
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
	 * @return \Framework\Database\QueryBuilder
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
	 * @return \Framework\Database\QueryBuilder
	 */
	public function references(string $table, string $column): self
	{
		self::$query .= " REFERENCES " . config('database.table_prefix') . "$table($column)";
        return $this;
	}
	
	/**
	 * add on update query attribute
	 *
	 * @return \Framework\Database\QueryBuilder
	 */
	public function onUpdate(): self
	{
        self::$query = rtrim(self::$query, ', ');
		self::$query .= " ON UPDATE, ";
        return $this;
	}
	
	/**
	 * add on delete query attribute
	 *
	 * @return \Framework\Database\QueryBuilder
	 */
	public function onDelete(): self
	{
        self::$query = rtrim(self::$query, ', ');
		self::$query .= " ON DELETE, ";
        return $this;
	}
	
	/**
	 * add cascade query attribute
	 *
	 * @return \Framework\Database\QueryBuilder
	 */
	public function cascade(): self
	{
		self::$query .= " CASCADE, ";
        return $this;
	}
	
	/**
	 * add set null attribute
	 *
	 * @return \Framework\Database\QueryBuilder
	 */
	public function setNull(): self
	{
		self::$query .= " SET NULL, ";
        return $this;
	}
    
    /**
     * create new table
     *
     * @return \Framework\Database\QueryBuilder
     */
    public function create(): self
    {
        if (config('database.timestamps')) {
            self::$query .= " created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
			    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP)";
        }

        if (self::$query[-1] !== ')') {
            self::$query .= ')';
        }

        self::$query .= " ENGINE='" . config('database.storage_engine') . "'";
		return $this;
    }

	/**
	 * where query
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\QueryBuilder
	 */
	public function where(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

		self::$query .= " WHERE $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}
        
    /**
     * where raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\QueryBuilder
     */
    public function whereRaw(string $query, array $args = []): self
    {
        return $this->rawQuery(' WHERE ' . $query, $args);
    }

	/**
	 * where not query
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\QueryBuilder
	 */
	public function whereNot(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

		self::$query .= " WHERE NOT $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * where query
	 *
	 * @param  string $column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function whereColumn(string $column): self
	{
		self::$query .= " WHERE $column ";
		return $this;
	}

	/**
	 * or query
	 *
	 * @param  string $column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function orColumn(string $column): self
	{
		self::$query .= " OR $column ";
		return $this;
	}

	/**
	 * and query
	 *
	 * @param  string $column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function andColumn(string $column): self
	{
		self::$query .= " AND $column ";
		return $this;
	}

	/**
	 * add is null attribute
	 *
	 * @return \Framework\Database\QueryBuilder
	 */
	public function isNull(): self
	{
		self::$query .= ' IS NULL ';
		return $this;
	}

	/**
	 * add is not null attribute 
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\Database\QueryBuilder
	 */
	public function notNull(): self
	{
		self::$query .= ' IS NOT NULL ';
		return $this;
	}

	/**
	 * in query
	 *
	 * @param  string $column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function in(array $values): self
	{
		self::$query .= ' IN (' . implode(',', $values) . ') ';
		return $this;
    }
    
	/**
	 * not in query
	 *
	 * @param  string $column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function notIn(array $values): self
	{
		self::$query .= ' NOT IN (' . implode(',', $values) . ') ';
		return $this;
    }

	/**
	 * between query
	 *
	 * @param  mixed $start
	 * @param  mixed $end
	 * @return \Framework\Database\QueryBuilder
	 */
	public function between($start, $end): self
	{
		self::$query .= " BETWEEN $start AND $end ";
		return $this;
    }
    
	/**
	 * not between query
	 *
	 * @param  mixed $start
	 * @param  mixed $end
	 * @return \Framework\Database\QueryBuilder
	 */
	public function notBetween($start, $end): self
	{
		self::$query .= " NOT BETWEEN $start AND $end ";
		return $this;
    }

	/**
	 * like query
	 *
	 * @param  mixed $value
	 * @return \Framework\Database\QueryBuilder
	 */
	public function like($value): self
	{
		self::$query .= " LIKE '%$value%' ";
		return $this;
	}

	/**
	 * not like query
	 *
	 * @param  mixed $value
	 * @return \Framework\Database\QueryBuilder
	 */
	public function notLike($value): self
	{
		self::$query .= " NOT LIKE '%$value%' ";
		return $this;
	}

	/**
	 * and query
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\QueryBuilder
	 */
	public function and(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

		self::$query .= " AND $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * or query
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\QueryBuilder
	 */
	public function or(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

		self::$query .= " OR $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}

	/**
	 * having query
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\QueryBuilder
	 */
	public function having(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

		self::$query .= " HAVING $column $operator ? ";
		self::$args[] = $value;
		return $this;
	}
        
    /**
     * having raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\QueryBuilder
     */
    public function havingRaw(string $query, array $args = []): self
    {
        return $this->rawQuery(' HAVING ' . $query, $args);
    }

	/**
	 * order by query
	 *
	 * @param  string $column
	 * @param  string $direction (ASC or DESC)
	 * @return \Framework\Database\QueryBuilder
	 */
	public function orderBy(string $column, string $direction): self
	{
		self::$query .= " ORDER BY $column " . strtoupper($direction);
		return $this;
	}

	/**
	 * group by query
	 *
	 * @param  string[] $columns
	 * @return \Framework\Database\QueryBuilder
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
	 * limit query
	 *
	 * @param  int $limit
	 * @param  int $offset
	 * @return \Framework\Database\QueryBuilder
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
	 * inner join query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function innerJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " INNER JOIN " . config('database.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * left join query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function leftJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " LEFT JOIN " . config('database.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * right join query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function rightJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " RIGHT JOIN " . config('database.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * full join query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function fullJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " FULL JOIN " . config('database.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

	/**
	 * full outer join query
	 *
	 * @param  string $table
	 * @param  string $first_column
     * @param  string $operator
	 * @param  string $second_column
	 * @return \Framework\Database\QueryBuilder
	 */
	public function outerJoin(string $table, string $first_column, string $operator, string $second_column): self
	{
		self::$query .= " FULL OUTER JOIN " . config('database.table_prefix') . "$table ON $first_column $operator $second_column";
		return $this;
	}

    /**
     * remove end comma if exists
     *
     * @return void
     */
    public function flush()
    {
        self::$query = rtrim(self::$query, ', ');
    }

    /**
     * check if row exists
     *
     * @return bool
     */
    public function exists(): bool
    {
        return !$this->fetch() === false;
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
	 * @return \Framework\Database\QueryBuilder
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
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\QueryBuilder
     */
    public function rawQuery(string $query, array $args = []): self
    {
        self::$query .= ' ' . $query;
        self::$args = array_merge(self::$args, $args);
        return $this;
    }
    
    /**
     * sub query
     *
     * @param  mixed $callback
     * @return \Framework\Database\QueryBuilder
     */
    public function subQuery(callable $callback): self
    {
        call_user_func_array($callback, [$this]);
        return $this;
    }
	
	/**
	 * execute query with arguments
	 *
	 * @return \PDOStatement
	 */
	public function execute(): \PDOStatement
	{
        $stmt = Database::connection()->statement(self::$query, self::$args);
		self::setQuery('');
		return $stmt;
    }

    /**
     * fetch single row
     *
     * @return mixed
     */
    public function fetch()
    {   
        return $this->execute()->fetch();
    }
    
    /**
     * fetch all rows
     *
     * @return array
     */
    public function fetchAll(): array
    {
        return $this->execute()->fetchAll();
    }
        
    /**
     * retrieves last inserted id
     *
     * @return int
     */
    public static function lastInsertedId(): int
    {
        return self::setQuery('SELECT LAST_INSERT_ID()')->execute()->fetchColumn();
    }
}

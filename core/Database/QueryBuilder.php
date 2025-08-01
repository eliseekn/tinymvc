<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database;

use Closure;
use Core\Database\Connection\Connection;
use Core\Enums\DatabaseDriver;
use Core\Enums\JoinMethod;
use Core\Exceptions\InvalidSQLQueryException;
use PDOStatement;

/**
 * Database query builder.
 */
class QueryBuilder
{
    protected static string $query;

    protected static $args;

    protected static string $table;

    protected static Connection $connection;

    public static function connection(?string $dbConnection = null): self
    {
        static::$connection = is_null($dbConnection)
            ? Connection::getInstance()
            : Connection::setInstance(new Connection($dbConnection));

        return new self;
    }

    protected static function setTable(string $name): string
    {
        return config('database.table_prefix').$name;
    }

    public static function table(string $name, ?string $dbConnection = null): self
    {
        static::$table = static::setTable($name);
        static::$args = [];

        return static::connection($dbConnection);
    }

    public static function createTable(string $name, ?string $dbConnection = null): self
    {
        static::$query = 'CREATE TABLE '.static::setTable($name).' (';

        return static::connection($dbConnection);
    }

    public static function dropTable(string $name, ?string $dbConnection = null): self
    {
        static::$query = 'DROP TABLE IF EXISTS '.static::setTable($name);

        if (static::$connection->getDriver() === DatabaseDriver::PGSQL) {
            static::$query .= ' CASCADE';
        }

        return static::connection($dbConnection);
    }

    public static function alter(string $table, ?string $dbConnection = null): self
    {
        static::$query = 'ALTER TABLE '.static::setTable($table);

        return static::connection($dbConnection);
    }

    public static function dropForeign(string $key, ?string $dbConnection = null): self
    {
        static::$query .= static::$connection->getDriver() === DatabaseDriver::PGSQL
            ? " DROP CONSTRAINT $key"
            : " DROP FOREIGN KEY $key";

        return static::connection($dbConnection);
    }

    public static function addColumn(?string $dbConnection = null): self
    {
        static::$query .= ' ADD COLUMN ';

        return static::connection($dbConnection);
    }

    public static function renameColumn(string $old, string $new, ?string $dbConnection = null): self
    {
        static::$query .= " RENAME COLUMN $old TO $new";

        return static::connection($dbConnection);
    }

    public static function deleteColumn(string $column, ?string $dbConnection = null): self
    {
        static::$query .= " DROP COLUMN $column";

        return static::connection($dbConnection);
    }

    public function insert(array $items): self
    {
        static::$query = 'INSERT INTO '.static::$table.' (';

        foreach ($items as $key => $value) {
            static::$query .= "$key, ";
        }

        static::$query = rtrim(static::$query, ', ');
        static::$query .= ') VALUES (';

        foreach ($items as $key => $value) {
            static::$query .= '?, ';
            static::$args[] = $value;
        }

        static::$query = rtrim(static::$query, ', ');
        static::$query .= ')';

        return $this;
    }

    public function update(array $items): self
    {
        static::$query = 'UPDATE '.static::$table.' SET ';
        $items = array_merge($items, ['updated_at' => carbon()->toDateTimeString()]);

        foreach ($items as $key => $value) {
            static::$query .= "$key = ?, ";
            static::$args[] = $value;
        }

        static::$query = rtrim(static::$query, ', ');

        return $this;
    }

    public function delete(): self
    {
        static::$query = 'DELETE FROM '.static::$table;

        return $this;
    }

    public function deleteWhere(string $column, $operator = null, $value = null): self
    {
        return $this->delete()->where($column, $operator, $value);
    }

    public function after(string $column): self
    {
        if (static::$connection->getDriver() !== DatabaseDriver::MYSQL) {
            return $this;
        }

        static::$query = rtrim(static::$query, ', ');
        static::$query .= " AFTER $column, ";

        return $this;
    }

    public function first(): self
    {
        if (static::$connection->getDriver() !== DatabaseDriver::MYSQL) {
            return $this;
        }

        static::$query = rtrim(static::$query, ', ');
        static::$query .= ' FIRST, ';

        return $this;
    }

    public function column(string $name, string $type): self
    {
        static::$query .= "$name $type, ";

        return $this;
    }

    public function autoIncrement(): self
    {
        if (static::$connection->getDriver() === DatabaseDriver::PGSQL) {
            return $this;
        }

        static::$query = rtrim(static::$query, ', ');

        static::$query .= match (static::$connection->getDriver()) {
            DatabaseDriver::MYSQL => ' AUTO_INCREMENT, ',
            default => ' AUTOINCREMENT, ',
        };

        return $this;
    }

    public function primaryKey(): self
    {
        static::$query = rtrim(static::$query, ', ');
        static::$query .= ' PRIMARY KEY, ';

        return $this;
    }

    public function null(): self
    {
        static::$query = rtrim(static::$query, ', ');
        static::$query .= ' NULL, ';

        return $this;
    }

    public function notNull(): self
    {
        static::$query = rtrim(static::$query, ', ');
        static::$query .= ' NOT NULL, ';

        return $this;
    }

    public function unique(): self
    {
        static::$query = rtrim(static::$query, ', ');
        static::$query .= ' UNIQUE, ';

        return $this;
    }

    public function default(string|int $default): self
    {
        static::$query = rtrim(static::$query, ', ');
        static::$query .= " DEFAULT '$default', ";

        return $this;
    }

    public function constraint(string $name): self
    {
        static::$query .= " CONSTRAINT $name";

        return $this;

    }

    public function addConstraint(string $name): self
    {
        static::$query .= " ADD CONSTRAINT $name";

        return $this;
    }

    public function foreignKey(string $column): self
    {
        static::$query .= " FOREIGN KEY ($column)";

        return $this;
    }

    public function references(string $table, string $column): self
    {
        static::$query .= ' REFERENCES '.static::setTable($table)."($column), ";

        return $this;
    }

    public function onUpdateCascade(): self
    {
        static::$query = rtrim(static::$query, ', ');
        static::$query .= ' ON UPDATE CASCADE, ';

        return $this;
    }

    public function onDeleteCascade(): self
    {
        static::$query = rtrim(static::$query, ', ');
        static::$query .= ' ON DELETE CASCADE, ';

        return $this;
    }

    public function onUpdateSetNull(): self
    {
        static::$query .= ' ON UPDATE SET NULL, ';

        return $this;
    }

    public function onDeleteSetNull(): self
    {
        static::$query .= ' ON DELETE SET NULL, ';

        return $this;
    }

    public function timestamps(): self
    {
        static::$query .= match (static::$connection->getDriver()) {
            DatabaseDriver::MYSQL => ' created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP NULL, ',
            DatabaseDriver::PGSQL => ' created_at TIMESTAMP NOT NULL DEFAULT NOW(), updated_at TIMESTAMP NULL, ',
            default => " created_at TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')), updated_at TIMESTAMP NULL, ",
        };

        return $this;
    }

    public function migrate(): false|PDOStatement
    {
        static::$query = rtrim(static::$query, ', ').')';

        if (static::$connection->getDriver() === DatabaseDriver::MYSQL) {
            static::$query .= " ENGINE='".static::$connection->getDB()->engine."'";
        }

        return $this->execute();
    }

    public function select(array|string $columns): self
    {
        $columns = parse_array($columns);
        static::$query = 'SELECT '.implode(',', $columns).' FROM '.static::$table;

        return $this;
    }

    public function addSelect(array|string $columns): self
    {
        if (! str_contains(static::$query, 'SELECT')) {
            throw new InvalidSQLQueryException;
        }

        $columns = parse_array($columns);
        static::$query = str_replace('SELECT ', 'SELECT '.implode(',', $columns).',', static::$query);

        return $this;
    }

    public function selectRaw(string $query): self
    {
        static::$query = 'SELECT '.$query.' FROM '.static::$table;

        return $this;
    }

    public function selectWhere(string $column, $operator = null, $value = null): self
    {
        return $this->select('*')->where($column, $operator, $value);
    }

    public function where(string $column, $operator = null, $value = null): self
    {
        if (! is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

        static::$query .= " WHERE $column $operator ? ";
        static::$args[] = $value;

        return $this;
    }

    public function whereRaw(string $query, array $args = []): self
    {
        return $this->rawQuery(' WHERE '.$query, $args);
    }

    public function andRaw(string $query, array $args = []): self
    {
        return $this->rawQuery(' AND '.$query, $args);
    }

    public function orRaw(string $query, array $args = []): self
    {
        return $this->rawQuery(' OR '.$query, $args);
    }

    public function whereNot(string $query, array $args = []): self
    {
        return $this->rawQuery(" WHERE NOT ($query) ", $args);
    }

    public function whereColumn(string $column): self
    {
        static::$query .= " WHERE $column ";

        return $this;
    }

    public function orColumn(string $column): self
    {
        static::$query .= " OR $column ";

        return $this;
    }

    public function andColumn(string $column): self
    {
        static::$query .= " AND $column ";

        return $this;
    }

    public function isNull(): self
    {
        static::$query .= ' IS NULL ';

        return $this;
    }

    public function isNotNull(): self
    {
        static::$query .= ' IS NOT NULL ';

        return $this;
    }

    public function in(array $values): self
    {
        $items = '';

        foreach ($values as $value) {
            $items .= '?, ';
            static::$args[] = $value;
        }

        $items = rtrim($items, ', ');

        static::$query .= ' IN ('.$items.') ';

        return $this;
    }

    public function notIn(array $values): self
    {
        $items = '';

        foreach ($values as $value) {
            $items .= '?, ';
            static::$args[] = $value;
        }

        $items = rtrim($items, ', ');

        static::$query .= ' NOT IN ('.$items.') ';

        return $this;
    }

    public function between($start, $end): self
    {
        static::$query .= " BETWEEN $start AND $end ";

        return $this;
    }

    public function notBetween($start, $end): self
    {
        static::$query .= " NOT BETWEEN $start AND $end ";

        return $this;
    }

    public function like($value): self
    {
        static::$query .= " LIKE '%$value%' ";

        return $this;
    }

    public function notLike($value): self
    {
        static::$query .= " NOT LIKE '%$value%' ";

        return $this;
    }

    public function and(string $column, $operator = null, $value = null): self
    {
        if (! is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

        static::$query .= " AND $column $operator ? ";
        static::$args[] = $value;

        return $this;
    }

    public function or(string $column, $operator = null, $value = null): self
    {
        if (! is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

        static::$query .= " OR $column $operator ? ";
        static::$args[] = $value;

        return $this;
    }

    public function having(string $column, $operator = null, $value = null): self
    {
        if (! is_null($operator) && is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

        static::$query .= " HAVING $column $operator ? ";
        static::$args[] = $value;

        return $this;
    }

    public function havingRaw(string $query, array $args = []): self
    {
        return $this->rawQuery(' HAVING '.$query, $args);
    }

    public function orderBy(string $column, string $direction): self
    {
        static::$query .= " ORDER BY $column ".strtoupper($direction);

        return $this;
    }

    public function groupBy(array|string $columns): self
    {
        $columns = parse_array($columns);
        static::$query .= ' GROUP BY '.implode(',', $columns);

        return $this;
    }

    public function limit(int $limit, ?int $offset = null): self
    {
        static::$query .= " LIMIT $limit";

        if (! is_null($offset)) {
            if (static::$connection->getDriver() === DatabaseDriver::PGSQL) {
                static::$query .= " OFFSET $offset";
            } else {
                static::$query .= ", $offset";
            }
        }

        return $this;
    }

    public function join(string $table, string $first_column, string $operator, string $second_column, string $method = JoinMethod::INNER): self
    {
        static::$query .= " $method JOIN ".static::setTable($table)." ON $first_column $operator $second_column";

        return $this;
    }

    public function crossJoin(string $table): self
    {
        static::$query .= " CROSS JOIN $table";

        return $this;
    }

    public function addJoin(string $table, string $first_column, string $operator, string $second_column, string $method = JoinMethod::INNER): self
    {
        if (! str_contains(static::$query, 'FROM')) {
            throw new InvalidSQLQueryException;
        }

        $query = trim(preg_replace('/\s+/', ' ', static::$query));
        $query = preg_replace('/\sFROM\s+[^ ]+/i', ' FROM '.static::$table.' '.$method.' JOIN '.static::setTable($table)." ON $first_column $operator $second_column", $query);

        static::$query = $query;

        return $this;
    }

    /**
     * Flush query string by removing latest comma.
     */
    public function flush(): self
    {
        static::$query = rtrim(static::$query, ', ');

        return $this;
    }

    public function exists(): bool
    {
        return $this->fetch() !== false;
    }

    public function toSQL(): array
    {
        $this->trimQuery();

        return [static::$query, static::$args];
    }

    public static function setQuery(string $query, array $args = [], ?string $dbConnection = null): self
    {
        static::$query = $query;
        static::$args = $args;

        return static::connection($dbConnection);
    }

    public function rawQuery(string $query, array $args = []): self
    {
        static::$query .= ' '.$query;
        static::$args = array_merge(static::$args, $args);

        return $this;
    }

    public function subQuery(Closure $callback): self
    {
        call_user_func_array($callback, [$this]);

        return $this;
    }

    public function subQueryWhen(bool $condition, ?Closure $callback = null): self
    {
        if ($condition === true) {
            $this->subQuery($callback);
        }

        return $this;
    }

    public function execute(): false|PDOStatement
    {
        $this->trimQuery();
        $stmt = static::$connection->executeQuery(static::$query, static::$args);
        static::setQuery('');

        return $stmt;
    }

    public function fetch(): mixed
    {
        return $this->execute()->fetch();
    }

    public function fetchAll(): array
    {
        return $this->execute()->fetchAll();
    }

    public function trimQuery(): void
    {
        static::$query = trim(static::$query);
        static::$query = str_replace('  ', ' ', static::$query);
    }

    public function dd(): void
    {
        dd($this->toSQL());
    }
}

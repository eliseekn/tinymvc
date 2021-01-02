<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Database;

use Framework\Http\Request;
use Framework\Support\Pager;
use Framework\Support\Metrics;

/**
 * Simplify use of builder
 */
class Model
{
    /**
     * @var string
     */
    public static $table = '';

    /**
     * @var \Framework\Database\Builder
     */
    protected static $query;

    /**
     * select rows
     *
     * @param  array $columns
     * @return \Framework\Database\Model
     */
    public static function select(array $columns = ['*']): self
    {
        self::$query = Builder::select(implode(',', $columns))->from(static::$table);
        return new self();
    }
    
    /**
     * retrieves single row
     *
     * @param  array $columns
     * @return void
     */
    public static function selectSingle(array $columns = ['*'])
    {
        return self::select($columns)->single();
    }
    
    /**
     * retrieves all rows
     *
     * @param  array $columns
     * @return array
     */
    public static function selectAll(array $columns = ['*']): array
    {
        return self::select($columns)->all();
    }

    /**
     * select rows with raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\Model
     */
    public static function selectRaw(string $query, array $args = []): self
    {
        self::$query = Builder::selectRaw($query, $args);
        return new self();
    }
    
    /**
     * generate select where query
     *
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return \Framework\Database\Model
     */
    public static function find($operator = null, $value = null): self
	{
        return self::select()->where('id', $operator, $value);
	}
    
    /**
     * generate select where query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return \Framework\Database\Model
     */
    public static function findBy(string $column, $operator = null, $value = null): self
	{
        return self::select()->where($column, $operator, $value);
	}
    
    /**
     * generate select where query
     *
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return mixed
     */
    public static function findSingle($operator = null, $value = null)
	{
        return self::select()->where('id', $operator, $value)->single();
	}
    
    /**
     * generate select where query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return mixed
     */
    public static function findSingleBy(string $column, $operator = null, $value = null)
	{
        return self::select()->where($column, $operator, $value)->single();
	}
    
    /**
     * generate select where query
     *
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return array
     */
    public static function findAll($operator = null, $value = null): array
	{
        return self::select()->where('id', $operator, $value)->all();
	}
    
    /**
     * generate select where query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return array
     */
    public static function findAllBy(string $column, $operator = null, $value = null): array
	{
        return self::select()->where($column, $operator, $value)->all();
	}
    
    /**
     * generate select where query with raw query
     *
	 * @param  string $query
	 * @param  array $args
     * @return \Framework\Database\Model
     */
    public static function findRaw(string $query, array $args = []): self
	{
        return self::select()->whereRaw($query, $args);
	}
    
    /**
     * generate search query
     *
     * @param  string $column
	 * @param  mixed $value
     * @return \Framework\Database\Model
     */
    public static function search(string $column, $value): self
    {
        return self::select()->whereLike($column, $value);
    }
    
    /**
     * insert new row
     *
     * @param  array $items
     * @return int
     */
    public static function insert(array $items): int
    {
        Builder::insert(static::$table, $items)->execute();
        return DBConnection::getInstance()->lastInsertedId();
    }
    
    /**
     * update row
     *
     * @param  array $items
     * @return \Framework\Database\Model
     */
    public static function update(array $items): self
    {
        self::$query = Builder::update(static::$table)->set($items);
        return new self();
    }
    
    /**
     * add DELETE clause
     *
     * @return \Framework\Database\Model
     */
    public static function delete(): self
    {
        self::$query = Builder::delete(static::$table);
        return new self();
    }
    
    /**
     * add DELETE and WEHRE clauses
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return void
     */
    public static function deleteWhere(string $column, $operator = null, $value = null): void
    {
        self::delete()->where($column, $operator, $value)->persist();
    }
    
    /**
     * get column count value
     *
     * @param  string $column
     * @return \Framework\Database\Model
     */
    public static function count(string $column = 'id')
    {
        return self::select(['COUNT(' . $column . ') AS value']);
    }
    
    /**
     * get column sum value
     *
     * @param  string $column
     * @return \Framework\Database\Model
     */
    public static function sum(string $column)
    {
        return self::select(['SUM(' . $column . ') AS value']);
    }
    
    /**
     * get column max value
     *
     * @param  string $column
     * @return \Framework\Database\Model
     */
    public static function max(string $column)
    {
        return self::select(['MAX(' . $column . ') AS value']);
    }
    
    /**
     * get column min value
     *
     * @param  string $column
     * @return \Framework\Database\Model
     */
    public static function min(string $column)
    {
        return self::select(['MIN(' . $column . ') AS value']);
    }
    
    /**
     * retrieves metrics
     *
     * @param  string $column
     * @param  string $type
     * @param  string $trends
     * @param  int $interval
     * @return array
     */
    public static function metrics(string $column, string $type, string $trends, int $interval = 0): array
    {
        return (new Metrics(static::$table))->getTrends($type, $column, $trends, $interval);
    }
    
	/**
	 * add WHERE clause
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Model
	 */
    public function where(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    self::$query->whereColumn($column)->isNull();
                    break;

                case 'not null':
                    self::$query->whereColumn($column)->isNotNull();
                    break;

                default:
                    self::$query->where($column, '=', $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    self::$query->whereColumn($column)->in($value);
                    break;

                case 'not in':
                    self::$query->whereColumn($column)->notIn($value);
                    break;

                case 'like':
                    self::$query->whereColumn($column)->like($value);
                    break;

                case 'not like':
                    self::$query->whereColumn($column)->notLike($value);
                    break;

                default:
                    self::$query->where($column, $operator, $value);
                    break;
            }
        }
        
		return $this;
	}

    /**
	 * add WHERE NOT clause
	 *
	 * @param  mixed $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Model
	 */
    public function whereNot(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            self::$query->whereNot($column, '=', $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            self::$query->whereNot($column, $operator, $value);
        }

		return $this;
    }
        
    /**
     * whereRaw
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\Model
     */
    public function whereRaw(string $query, array $args = []): self
    {
        self::$query->whereRaw($query, $args);
        return $this;
    }

	/**
	 * add AND clause
	 *
	 * @param  mixed $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Model
	 */
    public function and(string $column, $operator = null, $value = null): self
    {
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    self::$query->andColumn($column)->isNull();
                    break;

                case 'not null':
                    self::$query->andColumn($column)->isNotNull();
                    break;

                default:
                    self::$query->and($column, '=', $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    self::$query->andColumn($column)->in($value);
                    break;

                case 'not in':
                    self::$query->andColumn($column)->notIn($value);
                    break;

                case 'like':
                    self::$query->andColumn($column)->like($value);
                    break;

                case 'not like':
                    self::$query->andColumn($column)->notLike($value);
                    break;

                default:
                    self::$query->and($column, $operator, $value);
                    break;
            }
        }

		return $this;
    }

	/**
	 * add OR clause
	 *
	 * @param  mixed $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Model
	 */
    public function or(string $column, $operator = null, $value = null): self
    {
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    self::$query->orColumn($column)->isNull();
                    break;

                case 'not null':
                    self::$query->orColumn($column)->isNotNull();
                    break;

                default:
                    self::$query->or($column, '=', $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    self::$query->orColumn($column)->in($value);
                    break;

                case 'not in':
                    self::$query->orColumn($column)->notIn($value);
                    break;

                case 'like':
                    self::$query->orColumn($column)->like($value);
                    break;

                case 'not like':
                    self::$query->orColumn($column)->notLike($value);
                    break;

                default:
                    self::$query->or($column, $operator, $value);
                    break;
            }
        }

		return $this;
    }

    /**
	 * add HAVING clause
	 *
	 * @param  mixed $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Model
	 */
    public function having(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            self::$query->having($column, '=', $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            self::$query->having($column, $operator, $value);
        }

		return $this;
	}
    
    /**
     * add WHERE BETWEEN clause for range
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Framework\Database\Model
     */
    public function whereBetween(string $column, $start, $end): self
    {
        self::$query->whereColumn($column)->between($start, $end);
        return $this;
    }
    
    /**
     * add WHERE NOT BETWEEN clause for range
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Framework\Database\Model
     */
    public function whereNotBetween(string $column, $start, $end): self
    {
        self::$query->whereColumn($column)->notBetween($start, $end);
        return $this;
    }

    /**
	 * add WHERE NULL clause
	 *
	 * @param  string $column
	 * @return \Framework\Database\Model
	 */
    public function whereNull(string $column): self
	{
        self::$query->whereColumn($column)->isNull();
		return $this;
	}

    /**
	 * add WHERE NOT NULL clause
	 *
	 * @param  string $column
	 * @return \Framework\Database\Model
	 */
    public function whereNotNull(string $column): self
	{
        self::$query->whereColumn($column)->isNotNull();
		return $this;
	}

    /**
	 * add WHERE LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\Database\Model
	 */
    public function whereLike(string $column, $value): self
	{
        self::$query->whereColumn($column)->like($value);
		return $this;
	}

    /**
	 * add WHERE NOT LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\Database\Model
	 */
    public function whereNotLike(string $column, $value): self
	{
        self::$query->whereColumn($column)->notLike($value);
		return $this;
	}

    /**
	 * add WHERE IN clause
	 *
	 * @param  string $column
	 * @param  array $values
	 * @return \Framework\Database\Model
	 */
    public function whereIn(string $column, array $values): self
	{
        self::$query->whereColumn($column)->in($values);
		return $this;
	}

    /**
	 * add WHERE NOT IN clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\Database\Model
	 */
    public function whereNotIn(string $column, $value): self
	{
        self::$query->whereColumn($column)->notIn($value);
		return $this;
	}

    /**
     * add INNER JOIN query
     *
     * @param  string $table
     * @param  string $first_column
     * @param  string $operator
     * @param  string $second_column
     * @param  string $type
     * @return \Framework\Database\Model
     */
    public function join(string $table, string $first_column, string $operator, string $second_column, string $type = 'inner'): self
    {
        $method = $type . 'Join';
        self::$query->$method($table, $second_column, $operator, $first_column);

        return $this;
    }
    
    /**
     * add ORDER BY clause
     *
     * @param  string $column
     * @param  string $direction
     * @return \Framework\Database\Model
     */
    public function orderBy(string $column, string $direction): self
    {
        self::$query->orderBy($column, $direction);
        return $this;
    }
    
    /**
     * add custom ORDER BY clause with DESC
     *
     * @param  string $column
     * @return \Framework\Database\Model
     */
    public function orderDesc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'desc');
    }
    
    /**
     * add custom ORDER BY clause with ASC 
     *
     * @param  string $column
     * @return \Framework\Database\Model
     */
    public function orderAsc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'asc');
    }
    
    /**
     * add GROUP clause
     *
     * @param  array $columns
     * @return \Framework\Database\Model
     */
    public function group(array $columns): self
    {
        self::$query->groupBy(implode(',', $columns));
        return $this;
    }

    /**
     * check if row exists
     *
     * @return bool
     */
    public function exists(): bool
    {
        return !$this->single() === false;
    }
    
    /**
     * fetch single row
     *
     * @return mixed
     */
    public function single()
    {
        return $this->persist()->fetch();
    }
    
    /**
     * fetch all rows
     *
     * @return array
     */
    public function all(): array
    {
        return $this->persist()->fetchAll();
    }
    
    /**
     * fetch first row
     *
     * @return mixed
     */
    public function first()
    {
        return $this->all()[0];
    }
    
    /**
     * fetch last row
     *
     * @return mixed
     */
    public function last()
    {
        $rows = $this->all();
        return end($rows);
    }

    /**
     * fetch range of rows
     *
     * @param  int $start
     * @param  int $end
     * @return array
     */
    public function range(int $start, int $end): array
    {
        self::$query->limit($start, $end);
        return $this->all();
    }
    
    /**
     * fetch first rows of range
     *
     * @param  int $value
     * @return array
     */
    public function take(int $value): array
    {
        return $this->range(0, $value);
    }

    /**
     * generate pagination
     *
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function paginate(int $items_per_pages): Pager
    {
        list($query, $args) = self::$query->toSQL();

        $page = empty(Request::getQuery('page')) ? 1 : Request::getQuery('page');
        $total_items = count(Builder::setQuery($query, $args)->execute()->fetchAll());
        $pagination = generate_pagination($page, $total_items, $items_per_pages);
        
        $items = $items_per_pages > 0 ? 
            Builder::setQuery($query, $args)->limit($pagination['first_item'], $items_per_pages)->execute()->fetchAll() : 
            Builder::setQuery($query, $args)->execute()->fetchAll();
        
        return new Pager($items, $pagination);
    }
    
    /**
     * execute query
     *
     * @return \PDOStatement
     */
    public function persist(): \PDOStatement
    {
        return self::$query->execute();
    }
    
    /**
     * get query and arguments
     *
     * @return array
     */
    public function toSQL(): array
    {
        return self::$query->toSQL();
    }

    /**
     * add custom query string with arguments
     *
     * @param  mixed $query
     * @param  mixed $args
     * @return \Framework\Database\Model
     */
    public function raw(string $query, array $args = []): self
    {
        self::$query->rawQuery($query, $args);
        return $this;
    }
    
    /**
     * generate sub query
     *
     * @param  mixed $function
     * @return \Framework\Database\Model
     */
    public function subQuery(callable $function): self
    {
        call_user_func_array($function, [$this]);
        return $this;
    }
}
<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

use Framework\HTTP\Request;
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
     * @var \Framework\ORM\Builder
     */
    protected static $query;

    /**
     * select rows
     *
     * @param  array $columns
     * @return \Framework\ORM\Model
     */
    public static function select(array $columns = ['*']): self
    {
        self::$query = Builder::select(implode(',', $columns))
            ->from(static::$table);

        return new self();
    }
    
    /**
     * generate select where = query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return \Framework\ORM\Model
     */
    public static function find(string $column, $operator = null, $value = null): self
	{
        return self::select()->where($column, $operator, $value);
	}
    
    /**
     * generate search query
     *
     * @param  string $column
	 * @param  mixed $value
     * @return \Framework\ORM\Model
     */
    public static function search(string $column, $value): self
    {
        return self::select()->like($column, $value);
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
        return Database::getInstance()->lastInsertedId();
    }
    
    /**
     * update row
     *
     * @param  array $items
     * @return \Framework\ORM\Model
     */
    public static function update(array $items): self
    {
        self::$query = Builder::update(static::$table)->set($items);
        return new self();
    }
    
    /**
     * add DELETE clause
     *
     * @return \Framework\ORM\Model
     */
    public static function delete(): self
    {
        self::$query = Builder::delete(static::$table);
        return new self();
    }
    
    /**
     * get column count value
     *
     * @param  string $column
     * @return \Framework\ORM\Model
     */
    public static function count(string $column = 'id')
    {
        return self::select(['COUNT(' . $column . ') AS value']);
    }
    
    /**
     * get column sum value
     *
     * @param  string $column
     * @return \Framework\ORM\Model
     */
    public static function sum(string $column)
    {
        return self::select(['SUM(' . $column . ') AS value']);
    }
    
    /**
     * get column max value
     *
     * @param  string $column
     * @return \Framework\ORM\Model
     */
    public static function max(string $column)
    {
        return self::select(['MAX(' . $column . ') AS value']);
    }
    
    /**
     * get column min value
     *
     * @param  string $column
     * @return \Framework\ORM\Model
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
     * @return array
     */
    public static function metrics(string $column, string $type, string $trends): array
    {
        return (new Metrics(static::$table))->getTrends($type, $column, $trends);
    }

	/**
	 * add WHERE clause
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function where(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            self::$query->where($column, '=', $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            self::$query->where($column, $operator, $value);
        }
        
		return $this;
	}

	/**
	 * add AND WHERE clause
	 *
	 * @param  mixed $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function andWhere(string $column, $operator = null, $value = null): self
    {
        if (!is_null($operator) && is_null($value)) {
            self::$query->and($column, '=', $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            self::$query->and($column, $operator, $value);
        }

		return $this;
    }

	/**
	 * add OR WHERE clause
	 *
	 * @param  mixed $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function orWhere(string $column, $operator = null, $value = null): self
    {
        if (!is_null($operator) && is_null($value)) {
            self::$query->or($column, '=', $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            self::$query->or($column, $operator, $value);
        }

		return $this;
    }
    
    /**
     * add custom WHERE clause for range
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Framework\ORM\Model
     */
    public function between(string $column, $start, $end): self
    {
        return $this->where($column, '>=', $start)->andWhere($column, '<=', $end);
    }

    /**
	 * add HAVING clause
	 *
	 * @param  mixed $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
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
	 * add LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function like(string $column, $value): self
	{
        self::$query->like($column, $value);
		return $this;
	}

    /**
	 * add NOT LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function notLike(string $column, $value): self
	{
        self::$query->notLike($column, $value);
		return $this;
	}

    /**
	 * add LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function orLike(string $column, $value): self
	{
        self::$query->orLike($column, $value);
		return $this;
	}

    /**
	 * add NOT LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function orNotLike(string $column, $value): self
	{
        self::$query->orNotLike($column, $value);
		return $this;
    }

    /**
     * add INNER JOIN query
     *
     * @param  string $table
     * @param  string $second_column
     * @param  string $first_column
     * @return \Framework\ORM\Model
     */
    public function join(string $table, string $second_column, string $first_column): self
    {
        self::$query->innerJoin($table, $second_column, $first_column);
        return $this;
    }

    /**
     * add LEFT JOIN query
     *
     * @param  string $table
     * @param  string $second_column
     * @param  string $first_column
     * @return \Framework\ORM\Model
     */
    public function leftJoin(string $table, string $second_column, string $first_column): self
    {
        self::$query->leftJoin($table, $second_column, $first_column);
        return $this;
    }
    
    /**
     * add ORDER BY clause
     *
     * @param  string $column
     * @param  string $direction
     * @return \Framework\ORM\Model
     */
    public function orderBy(string $column, string $direction = 'DESC'): self
    {
        self::$query->orderBy($column, $direction);
        return $this;
    }
    
    /**
     * add custom ORDER BY clause with DESC
     *
     * @param  string $column
     * @return \Framework\ORM\Model
     */
    public function orderDesc(string $column = 'id'): self
    {
        return $this->orderBy($column);
    }
    
    /**
     * add custom ORDER BY clause with ASC 
     *
     * @param  string $column
     * @return \Framework\ORM\Model
     */
    public function orderAsc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'ASC');
    }
    
    /**
     * add GROUP clause
     *
     * @param  array $columns
     * @return \Framework\ORM\Model
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
        $items = $this->all();
        return $items[count($items) - 1];
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
     * fetch first items of range
     *
     * @param  int $value
     * @return array
     */
    public function firstOf(int $value): array
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
        list($query, $args) = self::$query->get();

        $page = empty(Request::getQuery('page')) ? 1 : Request::getQuery('page');
        $total_items = count(Builder::query($query, $args)->execute()->fetchAll());
        $pagination = generate_pagination($page, $total_items, $items_per_pages);
        
        $items = $items_per_pages > 0 ? 
            Builder::query($query, $args)->limit($pagination['first_item'], $items_per_pages)->execute()->fetchAll() : 
            Builder::query($query, $args)->execute()->fetchAll();
        
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
}

<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

use Framework\HTTP\Request;
use Framework\Support\Pager;

/**
 * Simplify use of builder
 */
class Model
{
    /**
     * @var string
     */
    protected static $table = '';

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
	 * @param  mixed $value
     * @return \Framework\ORM\Model
     */
    public static function find(string $column, $value): self
	{
        return self::select()->where($column, '=', $value);
	}
    
    /**
     * generate select where and query
     *
	 * @param  string $column
	 * @param  mixed $value
     * @return \Framework\ORM\Model
     */
    public static function findMany(array $first, array $and): self
	{
        $model = self::select()->where($first[0], '=', $first[1]);

        foreach ($and as $column => $value) {
            $model->and($column, '=', $value);
        }

        return $model;
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
	 * add WHERE clause
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function where(string $column, string $operator, $value): self
	{
        self::$query->where($column, $operator, $value);
		return $this;
	}

	/**
	 * add AND WHERE clause
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function and(string $column, string $operator, $value): self
    {
        self::$query->and($column, $operator, $value);
		return $this;
    }

	/**
	 * add OR WHERE clause
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function or(string $column, string $operator, $value): self
    {
        self::$query->or($column, $operator, $value);
		return $this;
    }

    /**
	 * add HAVING clause
	 *
	 * @param  string $column
	 * @param  string $operator
	 * @param  mixed $value
	 * @return \Framework\ORM\Model
	 */
    public function having(string $column, string $operator, $value): self
	{
        self::$query->having($column, $operator, $value);
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
     * add ORDER BY clause
     *
     * @param  string $direction
     * @return \Framework\ORM\Model
     */
    public function order(string $direction = 'DESC'): self
    {
        return $this->orderBy('id', $direction);
    }
    
    /**
     * add GROUP clause
     *
     * @param  string $column
     * @return \Framework\ORM\Model
     */
    public function group(string $column): self
    {
        self::$query->groupBy($column);
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
        return self::$query->execute()->fetch();
    }
    
    /**
     * fetch all rows
     *
     * @return array
     */
    public function all(): array
    {
        return self::$query->execute()->fetchAll();
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
     * @return void
     */
    public function persist(): void
    {
        self::$query->execute();
    }
}

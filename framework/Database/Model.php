<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Database;

use Exception;
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
     * @var \Framework\Database\Builder $builder
     */
    protected static $builder;

    /**
     * select rows
     *
     * @param  array $columns
     * @return \Framework\Database\Model
     */
    public static function select(array $columns = ['*']): self
    {
        self::$builder = Builder::select(implode(',', $columns))->from(static::$table);
        return new self();
    }
    
    /**
     * retrieves single row
     *
     * @param  array $columns
     * @return mixed
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
        self::$builder = Builder::selectRaw($query, $args);
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
     * retrieves data or throw exception if not exists
     *
     * @param  string $column
     * @param  mixed $operator
     * @param  mixed $value
     * @return mixed
     */
    public static function findOrFail(string $column, $operator = null, $value = null)
    {
        $result = self::findSingleBy($column, $operator, $value);

        if ($result === false) {
            throw new Exception('Data not found.');
        }

        return $result;
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
     * generate or search query
     *
     * @param  string $column
	 * @param  mixed $value
     * @return \Framework\Database\Model
     */
    public static function orSearch(string $column, $value): self
    {
        return self::select()->or($column, 'like', $value);
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
        return DB::connection(config('db.name'))->lastInsertedId();
    }
    
    /**
     * update row
     *
     * @param  array $items
     * @return \Framework\Database\Model
     */
    public static function update(array $items): self
    {
        self::$builder = Builder::update(static::$table)->set($items);
        return new self();
    }

    /**
     * update row with where clause
     *
	 * @param  array $data
     * @param  array $items
     * @return void
     */
    public static function updateBy(array $data, array $items): void
    {
        if (!isset($data[2])) {
            $data[2] = null;
        }

        if (self::findBy($data[0], $data[1], $data[2])->exists()) {
            self::update($items)->where($data[0], $data[1], $data[2])->persist();
        }
    }
    
    /**
     * update row with where clause
     *
	 * @param  mixed $id
     * @param  array $items
     * @return void
     */
    public static function updateIfExists($id, array $items): void
    {
        self::updateBy(['id', $id], $items);
    }
    
    /**
     * add DELETE clause
     *
     * @return \Framework\Database\Model
     */
    public static function delete(): self
    {
        self::$builder = Builder::delete(static::$table);
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
    public static function deleteBy(string $column, $operator = null, $value = null): void
    {
        if (self::findBy($column, $operator, $value)->exists()) {
            self::delete()->where($column, $operator, $value)->persist();
        }
    }
    
    /**
     * add DELETE and WEHRE clauses
     *
	 * @param  mixed $id
     * @return void
     */
    public static function deleteIfExists($id): void
    {
        self::deleteBy('id', $id);
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
     * @param  array|null $query
     * @return array
     */
    public static function metrics(string $column, string $type, string $trends, int $interval = 0, array $query = null): array
    {
        return (new Metrics(static::$table))->getTrends($type, $column, $trends, $interval, $query);
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
                    self::$builder->whereColumn($column)->isNull();
                    break;

                case 'not null':
                    self::$builder->whereColumn($column)->notNull();
                    break;

                default:
                    self::$builder->where($column, $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    self::$builder->whereColumn($column)->in($value);
                    break;

                case 'not in':
                    self::$builder->whereColumn($column)->notIn($value);
                    break;

                case 'like':
                    self::$builder->whereColumn($column)->like($value);
                    break;

                case 'not like':
                    self::$builder->whereColumn($column)->notLike($value);
                    break;

                default:
                    self::$builder->where($column, $operator, $value);
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
            self::$builder->whereNot($column, $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            self::$builder->whereNot($column, $operator, $value);
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
        self::$builder->whereRaw($query, $args);
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
                    self::$builder->andColumn($column)->isNull();
                    break;

                case 'not null':
                    self::$builder->andColumn($column)->notNull();
                    break;

                default:
                    self::$builder->and($column, $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    self::$builder->andColumn($column)->in($value);
                    break;

                case 'not in':
                    self::$builder->andColumn($column)->notIn($value);
                    break;

                case 'like':
                    self::$builder->andColumn($column)->like($value);
                    break;

                case 'not like':
                    self::$builder->andColumn($column)->notLike($value);
                    break;

                default:
                    self::$builder->and($column, $operator, $value);
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
                    self::$builder->orColumn($column)->isNull();
                    break;

                case 'not null':
                    self::$builder->orColumn($column)->notNull();
                    break;

                default:
                    self::$builder->or($column, $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    self::$builder->orColumn($column)->in($value);
                    break;

                case 'not in':
                    self::$builder->orColumn($column)->notIn($value);
                    break;

                case 'like':
                    self::$builder->orColumn($column)->like($value);
                    break;

                case 'not like':
                    self::$builder->orColumn($column)->notLike($value);
                    break;

                default:
                    self::$builder->or($column, $operator, $value);
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
            self::$builder->having($column, $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            self::$builder->having($column, $operator, $value);
        }

		return $this;
	}
        
    /**
     * havingRaw
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\Model
     */
    public function havingRaw(string $query, array $args = []): self
    {
        self::$builder->havingRaw($query, $args);
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
        self::$builder->whereColumn($column)->between($start, $end);
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
        self::$builder->whereColumn($column)->notBetween($start, $end);
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
        self::$builder->whereColumn($column)->isNull();
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
        self::$builder->whereColumn($column)->notNull();
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
        self::$builder->whereColumn($column)->like($value);
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
        self::$builder->whereColumn($column)->notLike($value);
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
        self::$builder->whereColumn($column)->in($values);
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
        self::$builder->whereColumn($column)->notIn($value);
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
        self::$builder->$method($table, $second_column, $operator, $first_column);

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
        self::$builder->orderBy($column, $direction);
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
        self::$builder->groupBy(implode(',', $columns));
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
        self::$builder->limit($start, $end);
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
        list($query, $args) = self::$builder->toSQL();

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
        return self::$builder->execute();
    }
    
    /**
     * get query and arguments
     *
     * @return array
     */
    public function toSQL(): array
    {
        return self::$builder->toSQL();
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
        self::$builder->rawQuery($query, $args);
        return $this;
    }
    
    /**
     * set query string and arguments
     *
     * @param  mixed $query
     * @param  mixed $args
     * @return \Framework\Database\Model
     */
    public static function query(string $query, array $args = []): self
    {
        self::$builder = Builder::setQuery($query, $args);
        return new self();
    }
    
    /**
     * generate sub query
     *
     * @param  mixed $callback
     * @return \Framework\Database\Model
     */
    public function subQuery(callable $callback): self
    {
        call_user_func_array($callback, [$this]);
        return $this;
    }
}

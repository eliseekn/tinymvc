<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use Exception;
use Core\Support\Pager;
use Core\Support\Metrics;

/**
 * Main repository class
 */
class Repository
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var \Core\Database\QueryBuilder
     */
    protected $qb;
    
    /**
     * __construct
     *
     * @param  string $table
     * @return void
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * select rows
     *
     * @param  string[] $columns
     * @return \Core\Database\Repository
     */
    public function select(string ...$columns): self
    {
        $this->qb = QueryBuilder::table($this->table)->select(...$columns);
        return $this;
    }
    
    /**
     * retrieves one row
     *
     * @param  string[] $columns
     * @return mixed
     */
    public function selectOne(string ...$columns)
    {
        return $this->select(...$columns)->one();
    }
    
    /**
     * retrieves all rows
     *
     * @param  string[] $columns
     * @return array
     */
    public function selectAll(string ...$columns): array
    {
        return $this->select(...$columns)->all();
    }

    /**
     * select rows with raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Core\Database\Repository
     */
    public function selectRaw(string $query, array $args = []): self
    {
        $this->qb = QueryBuilder::table($this->table)->selectRaw($query, $args);
        return $this;
    }
    
    /**
     * generate select where query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return \Core\Database\Repository
     */
    public function findWhere(string $column, $operator = null, $value = null): self
	{
        return $this->select('*')->where($column, $operator, $value);
	}
    
    /**
     * generate select where query
     *
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return \Core\Database\Repository
     */
    public function find($operator = null, $value = null): self
	{
        return $this->findWhere('id', $operator, $value);
	}
    
    /**
     * generate select where query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return mixed
     */
    public function findOneWhere(string $column, $operator = null, $value = null)
	{
        return $this->select('*')->where($column, $operator, $value)->one();
	}
    
    /**
     * generate select where query
     *
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return mixed
     */
    public function findOne($operator = null, $value = null)
	{
        return $this->findOneWhere('id', $operator, $value);
	}
    
    /**
     * generate select where query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return array
     */
    public function findAllWhere(string $column, $operator = null, $value = null): array
	{
        return $this->select('*')->where($column, $operator, $value)->all();
	}
    
    /**
     * generate select where query
     *
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return array
     */
    public function findAll($operator = null, $value = null): array
	{
        return $this->findAllWhere('id', $operator, $value);
	}
    
    /**
     * generate select where query with raw query
     *
	 * @param  string $query
	 * @param  array $args
     * @return \Core\Database\Repository
     */
    public function findRaw(string $query, array $args = []): self
	{
        return $this->select('*')->whereRaw($query, $args);
	}

    /**
     * find columns with glue option
     *
     * @param  array $items
	 * @param  string $glue
     * @return \Core\Database\Repository
     */
    public function findMany(array $items, string $glue = 'or'): self
    {
        $result = $this->select('*');
        $first_item = key($items);

        foreach ($items as $column => $value) {
            if ($items[$first_item] === $value) {
                $result->where($column, $value);
            } else {
                $result->$glue($column, $value);
            }
        }

        return $result;
    }
    
    /**
     * retrieves data or throw exception if not exists
     *
     * @param  string $column
     * @param  mixed $operator
     * @param  mixed $value
     * @return mixed
     * 
     * @throws Exception
     */
    public function findOrFail(string $column, $operator = null, $value = null)
    {
        $result = $this->findOneWhere($column, $operator, $value);

        if ($result === false) {
            throw new Exception('Record not found in database.');
        }

        return $result;
    }
    
    /**
     * insert items if id not found
     *
     * @param  mixed $id
     * @param  array $items
     * @return mixed
     */
    public function findOrCreate($id, array $items)
    {
        try {
            $result = $this->findOrFail('id', $id);
        } catch (Exception $e) {
            $result = $this->insert($items);
        }

        return $result;
    }

    /**
     * add select between query
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Core\Database\Repository
     */
    public function findBetween(string $column, $start = null, $end = null): self
    {
        return $this->select('*')->whereBetween($column, $start, $end);
    }
    
    /**
     * add select between query
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return mixed
     */
    public function findOneBetween(string $column, $start = null, $end = null)
    {
        return $this->select('*')->whereBetween($column, $start, $end)->one();
    }
    
    /**
     * add select between query
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return array
     */
    public function findAllBetween(string $column, $start = null, $end = null): array
    {
        return $this->select('*')->whereBetween($column, $start, $end)->all();
    }
    
    /**
     * add select not between query
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Core\Database\Repository
     */
    public function findNotBetween(string $column, $start = null, $end = null): self
    {
        return $this->select('*')->whereNotBetween($column, $start, $end);
    }
    
    /**
     * add select not between query
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return mixed
     */
    public function findOneNotBetween(string $column, $start = null, $end = null)
    {
        return $this->select('*')->whereNotBetween($column, $start, $end)->one();
    }
    
    /**
     * add select not between query
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return array
     */
    public function findAllNotBetween(string $column, $start = null, $end = null): array
    {
        return $this->select('*')->whereNotBetween($column, $start, $end)->all();
    }
    
    /**
     * generate search query
     *
     * @param  array $items
	 * @param  string $glue
     * @return \Core\Database\Repository
     */
    public function search(array $items, string $glue = 'or'): self
    {
        $result = $this->select('*');
        $first_item = key($items);

        foreach ($items as $column => $value) {
            if ($items[$first_item] === $value) {
                $result->whereLike($column, $value);
            } else {
                $result->$glue($column, 'like', $value);
            }
        }

        return $result;
    }
    
    /**
     * insert new row
     *
     * @param  array $items
     * @return int
     */
    public function insert(array $items): int
    {
        QueryBuilder::table($this->table)->insert($items)->execute();
        return QueryBuilder::lastInsertedId();
    }
    
    /**
     * update row
     *
     * @param  array $items
     * @return \Core\Database\Repository
     */
    public function update(array $items): self
    {
        $this->qb = QueryBuilder::table($this->table)->update($items);
        return $this;
    }

    /**
     * update row with where clause
     *
	 * @param  array $data
     * @param  array $items
     * @return bool
     */
    public function updateWhere(array $data, array $items): bool
    {
        if (!isset($data[2])) {
            $data[2] = null;
        }

        if (!$this->findWhere($data[0], $data[1], $data[2])->exists()) {
            return false;
        }

        $this->update($items)->where($data[0], $data[1], $data[2])->execute();
        return true;
    }
    
    /**
     * update row with where clause
     *
	 * @param  mixed $id
     * @param  array $items
     * @return bool
     */
    public function updateIfExists($id, array $items): bool
    {
        return $this->updateWhere(['id', $id], $items);
    }
    
    /**
     * create items if id not found to update data
     *
     * @param  mixed $id
     * @param  array $items
     * @return mixed
     */
    public function updateOrCreate($id, array $items)
    {
        try {
            $this->findOrFail('id', $id);
            $result = $this->updateIfExists($id, $items);
        } catch (Exception $e) {
            $result = $this->insert($items);
        }

        return $result;
    }
    
    /**
     * add delete clause
     *
     * @return \Core\Database\Repository
     */
    public function delete(): self
    {
        $this->qb = QueryBuilder::table($this->table)->delete();
        return $this;
    }
    
    /**
     * add delete and where clauses
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return bool
     */
    public function deleteWhere(string $column, $operator = null, $value = null): bool
    {
        if (!$this->findWhere($column, $operator, $value)->exists()) {
            return false;
        }

        $this->delete()->where($column, $operator, $value)->execute();
        return true;
    }
    
    /**
     * add delete and where clauses
     *
	 * @param  mixed $id
     * @return bool
     */
    public function deleteIfExists($id): bool
    {
        return $this->deleteWhere('id', $id);
    }
    
    /**
     * get column count value
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function count(string $column = 'id'): self
    {
        return $this->select('COUNT(' . $column . ') AS value');
    }
    
    /**
     * get column sum value
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function sum(string $column): self
    {
        return $this->select('SUM(' . $column . ') AS value');
    }
    
    /**
     * get column max value
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function max(string $column): self
    {
        return $this->select('MAX(' . $column . ') AS value');
    }
    
    /**
     * get column min value
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function min(string $column): self
    {
        return $this->select('MIN(' . $column . ') AS value');
    }
    
    /**
     * retrieves metrics
     *
     * @param  string $column
     * @param  string $type
     * @param  string $period
     * @param  int $interval
     * @param  array|null $query
     * @return array
     */
    public function metrics(string $column, string $type, string $period, int $interval = 0, ?array $query = null): array
    {
        return (new Metrics($this->table))->trends($column, $type, $period, $interval, $query);
    }
    
	/**
	 * add where clause
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Core\Database\Repository
	 */
    public function where(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    $this->qb->whereColumn($column)->isNull();
                    break;

                case '!null':
                    $this->qb->whereColumn($column)->notNull();
                    break;

                default:
                    $this->qb->where($column, $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    $this->qb->whereColumn($column)->in($value);
                    break;

                case '!in':
                    $this->qb->whereColumn($column)->notIn($value);
                    break;

                case 'like':
                    $this->qb->whereColumn($column)->like($value);
                    break;

                case '!like':
                    $this->qb->whereColumn($column)->notLike($value);
                    break;

                case 'between':
                    if (is_array($value)) {
                        $this->qb->whereColumn($column)->between($value[0], $value[1]);
                    }
                    break;

                case '!between':
                    if (is_array($value)) {
                        $this->qb->whereColumn($column)->notBetween($value[0], $value[1]);
                    }
                    break;

                default:
                    $this->qb->where($column, $operator, $value);
                    break;
            }
        }
        
		return $this;
	}

	/**
	 * add and clause
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Core\Database\Repository
	 */
    public function and(string $column, $operator = null, $value = null): self
    {
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    $this->qb->andColumn($column)->isNull();
                    break;

                case '!null':
                    $this->qb->andColumn($column)->notNull();
                    break;

                default:
                    $this->qb->and($column, $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    $this->qb->andColumn($column)->in($value);
                    break;

                case '!in':
                    $this->qb->andColumn($column)->notIn($value);
                    break;

                case 'like':
                    $this->qb->andColumn($column)->like($value);
                    break;

                case '!like':
                    $this->qb->andColumn($column)->notLike($value);
                    break;

                case 'between':
                    if (is_array($value)) {
                        $this->qb->whereColumn($column)->between($value[0], $value[1]);
                    }
                    break;

                case '!between':
                    if (is_array($value)) {
                        $this->qb->whereColumn($column)->notBetween($value[0], $value[1]);
                    }
                    break;

                default:
                    $this->qb->and($column, $operator, $value);
                    break;
            }
        }

		return $this;
    }

	/**
	 * add or clause
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Core\Database\Repository
	 */
    public function or(string $column, $operator = null, $value = null): self
    {
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    $this->qb->orColumn($column)->isNull();
                    break;

                case '!null':
                    $this->qb->orColumn($column)->notNull();
                    break;

                default:
                    $this->qb->or($column, $operator);
                    break;
            }
        }

        else if (!is_null($operator) && !is_null($value)) {
            switch(strtolower($operator)) {
                case 'in':
                    $this->qb->orColumn($column)->in($value);
                    break;

                case '!in':
                    $this->qb->orColumn($column)->notIn($value);
                    break;

                case 'like':
                    $this->qb->orColumn($column)->like($value);
                    break;

                case '!like':
                    $this->qb->orColumn($column)->notLike($value);
                    break;

                case 'between':
                    if (is_array($value)) {
                        $this->qb->whereColumn($column)->between($value[0], $value[1]);
                    }
                    break;

                case '!between':
                    if (is_array($value)) {
                        $this->qb->whereColumn($column)->notBetween($value[0], $value[1]);
                    }
                    break;

                default:
                    $this->qb->or($column, $operator, $value);
                    break;
            }
        }

		return $this;
    }

    /**
	 * add where not clause
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Core\Database\Repository
	 */
    public function whereNot(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            $this->qb->whereNot($column, $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            $this->qb->whereNot($column, $operator, $value);
        }

		return $this;
    }
    
    /**
     * add where >= clause
     *
     * @param  string $column
     * @param  mixed $value
     * @return \Core\Database\Repository
     */
    public function whereGreater(string $column, $value): self
    {
        return $this->where($column, '>=', $value);
    }
    
    /**
     * add and >= clause
     *
     * @param  string $column
     * @param  mixed $value
     * @return \Core\Database\Repository
     */
    public function andGreater(string $column, $value): self
    {
        return $this->and($column, '>=', $value);
    }
    
    /**
     * add or >= clause
     *
     * @param  string $column
     * @param  mixed $value
     * @return \Core\Database\Repository
     */
    public function orGreater(string $column, $value): self
    {
        return $this->or($column, '>=', $value);
    }
        
    /**
     * add where =< clause
     *
     * @param  string $column
     * @param  mixed $value
     * @return \Core\Database\Repository
     */
    public function whereLower(string $column, $value): self
    {
        return $this->where($column, '<=', $value);
    }
        
    /**
     * add and =< clause
     *
     * @param  string $column
     * @param  mixed $value
     * @return \Core\Database\Repository
     */
    public function andLower(string $column, $value): self
    {
        return $this->and($column, '<=', $value);
    }
        
    /**
     * add or =< clause
     *
     * @param  string $column
     * @param  mixed $value
     * @return \Core\Database\Repository
     */
    public function orLower(string $column, $value): self
    {
        return $this->or($column, '<=', $value);
    }
        
    /**
     * whereRaw
     *
     * @param  string $query
     * @param  array $args
     * @return \Core\Database\Repository
     */
    public function whereRaw(string $query, array $args = []): self
    {
        $this->qb->whereRaw($query, $args);
        return $this;
    }
        
    /**
     * andRaw
     *
     * @param  string $query
     * @param  array $args
     * @return \Core\Database\Repository
     */
    public function andRaw(string $query, array $args = []): self
    {
        $this->qb->andRaw($query, $args);
        return $this;
    }
        
    /**
     * orRaw
     *
     * @param  string $query
     * @param  array $args
     * @return \Core\Database\Repository
     */
    public function orRaw(string $query, array $args = []): self
    {
        $this->qb->orRaw($query, $args);
        return $this;
    }

    /**
	 * add HAVING clause
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Core\Database\Repository
	 */
    public function having(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            $this->qb->having($column, $operator);
        }

        else if (!is_null($operator) && !is_null($value)) {
            $this->qb->having($column, $operator, $value);
        }

		return $this;
	}
        
    /**
     * havingRaw
     *
     * @param  string $query
     * @param  array $args
     * @return \Core\Database\Repository
     */
    public function havingRaw(string $query, array $args = []): self
    {
        $this->qb->havingRaw($query, $args);
        return $this;
    }
    
    /**
     * add where BETWEEN clause for range
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Core\Database\Repository
     */
    public function whereBetween(string $column, $start, $end): self
    {
        $this->qb->whereColumn($column)->between($start, $end);
        return $this;
    }
    
    /**
     * add where not BETWEEN clause for range
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Core\Database\Repository
     */
    public function whereNotBetween(string $column, $start, $end): self
    {
        $this->qb->whereColumn($column)->notBetween($start, $end);
        return $this;
    }
    
    /**
	 * add where NULL clause
	 *
	 * @param  string $column
	 * @return \Core\Database\Repository
	 */
    public function whereNull(string $column): self
	{
        $this->qb->whereColumn($column)->isNull();
		return $this;
	}

    /**
	 * add where not NULL clause
	 *
	 * @param  string $column
	 * @return \Core\Database\Repository
	 */
    public function whereNotNull(string $column): self
	{
        $this->qb->whereColumn($column)->notNull();
		return $this;
	}

    /**
	 * add where LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Core\Database\Repository
	 */
    public function whereLike(string $column, $value): self
	{
        $this->qb->whereColumn($column)->like($value);
		return $this;
	}

    /**
	 * add where not LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Core\Database\Repository
	 */
    public function whereNotLike(string $column, $value): self
	{
        $this->qb->whereColumn($column)->notLike($value);
		return $this;
	}

    /**
	 * add where IN clause
	 *
	 * @param  string $column
	 * @param  array $values
	 * @return \Core\Database\Repository
	 */
    public function whereIn(string $column, array $values): self
	{
        $this->qb->whereColumn($column)->in($values);
		return $this;
	}

    /**
	 * add where not IN clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Core\Database\Repository
	 */
    public function whereNotIn(string $column, $value): self
	{
        $this->qb->whereColumn($column)->notIn($value);
		return $this;
	}

    /**
     * add INNER JOIN query
     *
     * @param  string $table
     * @param  string $first_column
     * @param  string $operator
     * @param  string $second_column
     * @param  string $method
     * @return \Core\Database\Repository
     */
    public function join(string $table, string $first_column, string $operator, string $second_column, string $method = 'inner'): self
    {
        $method = $method . 'Join';
        $this->qb->$method($table, $second_column, $operator, $first_column);
        return $this;
    }
    
    /**
     * add orDER BY clause
     *
     * @param  string $column
     * @param  string $direction
     * @return \Core\Database\Repository
     */
    public function orderBy(string $column, string $direction): self
    {
        $this->qb->orderBy($column, $direction);
        return $this;
    }
    
    /**
     * add custom ORDER BY clause with DESC
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function orderDesc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'desc');
    }
    
    /**
     * add custom ORDER BY clause with ASC 
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function orderAsc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'asc');
    }
    
    /**
     * add custom ORDER BY created_at column clause with ASC
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function newest(string $column = 'created_at'): self
    {
        return $this->orderAsc($column);
    }
    
    /**
     * add custom ORDER BY created_at column clause with DESC
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function oldest(string $column = 'created_at'): self
    {
        return $this->orderDesc($column);
    }
    
    /**
     * add custom ORDER BY id column clause with ASC
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function earliest(string $column = 'id'): self
    {
        return $this->orderAsc($column);
    }
    
    /**
     * add custom ORDER BY id column clause with DESC
     *
     * @param  string $column
     * @return \Core\Database\Repository
     */
    public function latest(string $column = 'id'): self
    {
        return $this->orderDesc($column);
    }
    
    /**
     * add GROUP clause
     *
     * @param  string[] $columns
     * @return \Core\Database\Repository
     */
    public function groupBy(string ...$columns): self
    {
        $this->qb->groupBy(...$columns);
        return $this;
    }

    /**
     * check if row exists
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->qb->exists();
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
        $this->qb->limit($start, $end);
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
     * @param  int $items_per_page
     * @return \Core\Support\Pager
     */
    public function paginate(int $items_per_page): Pager
    {
        list($query, $args) = $this->qb->toSQL();

        $total_items = count(QueryBuilder::setQuery($query, $args)->fetchAll());
        $pager = new Pager($total_items, $items_per_page);
        
        $items = $items_per_page > 0 
            ? QueryBuilder::setQuery($query, $args)->limit($pager->getFirstItem(), $items_per_page)->fetchAll() 
            : QueryBuilder::setQuery($query, $args)->fetchAll();
        
        return $pager->setItems($items);
    }
    
    /**
     * fetch one row
     *
     * @return mixed
     */
    public function one()
    {
        return $this->execute()->fetch();
    }
    
    /**
     * fetch all rows
     *
     * @return array
     */
    public function all(): array
    {
        return $this->execute()->fetchAll();
    }
    
    /**
     * get query and arguments
     *
     * @return array
     */
    public function toSQL(): array
    {
        return $this->qb->toSQL();
    }

    /**
     * add custom query string with arguments
     *
     * @param  string $query
     * @param  array $args
     * @return \Core\Database\Repository
     */
    public function rawQuery(string $query, array $args = []): self
    {
        $this->qb->rawQuery($query, $args);
        return $this;
    }
    
    /**
     * set query string and arguments
     *
     * @param  string $query
     * @param  array $args
     * @return \Core\Database\Repository
     */
    public function setQuery(string $query, array $args = []): self
    {
        $this->qb = QueryBuilder::setQuery($query, $args);
        return $this;
    }
    
    /**
     * generate sub query
     *
     * @param  \Closure $callback
     * @return \Core\Database\Repository
     */
    public function subQuery($callback): self
    {
        call_user_func_array($callback, [$this]);
        return $this;
    }
    
    /**
     * execute query
     *
     * @return \PDOStatement
     */
    public function execute(): \PDOStatement
    {
        return $this->qb->execute();
    }
}

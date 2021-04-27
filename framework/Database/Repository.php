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
 * Main repository class
 */
class Repository
{
    /**
     * @var string $table
     */
    protected $table;

    /**
     * @var \Framework\Database\QueryBuilder $qb
     */
    protected $qb;
    
    /**
     * __construct
     *
     * @param  mixed $table
     * @return void
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * select rows
     *
     * @param  array $columns
     * @return \Framework\Database\Repository
     */
    public function select(array $columns = ['*']): self
    {
        $this->qb = QueryBuilder::table($this->table)->select(implode(',', $columns));
        return $this;
    }
    
    /**
     * retrieves single row
     *
     * @param  array $columns
     * @return mixed
     */
    public function selectSingle(array $columns = ['*'])
    {
        return $this->select($columns)->single();
    }
    
    /**
     * retrieves all rows
     *
     * @param  array $columns
     * @return array
     */
    public function selectAll(array $columns = ['*']): array
    {
        return $this->select($columns)->all();
    }

    /**
     * select rows with raw query
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\Repository
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
     * @return \Framework\Database\Repository
     */
    public function findBy(string $column, $operator = null, $value = null): self
	{
        return $this->select()->where($column, $operator, $value);
	}
    
    /**
     * generate select where query
     *
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return \Framework\Database\Repository
     */
    public function find($operator = null, $value = null): self
	{
        return $this->findBy('id', $operator, $value);
	}
    
    /**
     * generate select where query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return mixed
     */
    public function findOneBy(string $column, $operator = null, $value = null)
	{
        return $this->select()->where($column, $operator, $value)->single();
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
        return $this->findOneBy('id', $operator, $value);
	}
    
    /**
     * generate select where query
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return array
     */
    public function findAllBy(string $column, $operator = null, $value = null): array
	{
        return $this->select()->where($column, $operator, $value)->all();
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
        return $this->findAllBy('id', $operator, $value);
	}
    
    /**
     * generate select where query with raw query
     *
	 * @param  string $query
	 * @param  array $args
     * @return \Framework\Database\Repository
     */
    public function findRaw(string $query, array $args = []): self
	{
        return $this->select()->whereRaw($query, $args);
	}

    /**
     * find columns with glue option
     *
     * @param  array $items
	 * @param  string $glue
     * @return \Framework\Database\Repository
     */
    public function findMany(array $items, string $glue = 'or'): self
    {
        $result = $this->select();
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
     */
    public function findOrFail(string $column, $operator = null, $value = null)
    {
        $result = $this->findOneBy($column, $operator, $value);

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
     * generate search query
     *
     * @param  array $items
	 * @param  string $glue
     * @return \Framework\Database\Repository
     */
    public function search(array $items, string $glue = 'or'): self
    {
        $result = $this->select();
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
     * @return \Framework\Database\Repository
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
    public function updateBy(array $data, array $items): bool
    {
        if (!isset($data[2])) {
            $data[2] = null;
        }

        if (!$this->findBy($data[0], $data[1], $data[2])->exists()) {
            return false;
        }

        $this->update($items)->where($data[0], $data[1], $data[2])->persist();
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
        return $this->updateBy(['id', $id], $items);
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
     * add DELETE clause
     *
     * @return \Framework\Database\Repository
     */
    public function delete(): self
    {
        $this->qb = QueryBuilder::table($this->table)->delete();
        return $this;
    }
    
    /**
     * add DELETE and WEHRE clauses
     *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
     * @return bool
     */
    public function deleteBy(string $column, $operator = null, $value = null): bool
    {
        if (!$this->findBy($column, $operator, $value)->exists()) {
            return false;
        }

        $this->delete()->where($column, $operator, $value)->persist();
        return true;
    }
    
    /**
     * add DELETE and WEHRE clauses
     *
	 * @param  mixed $id
     * @return bool
     */
    public function deleteIfExists($id): bool
    {
        return $this->deleteBy('id', $id);
    }
    
    /**
     * get column count value
     *
     * @param  string $column
     * @return \Framework\Database\Repository
     */
    public function count(string $column = 'id'): self
    {
        return $this->select(['COUNT(' . $column . ') AS value']);
    }
    
    /**
     * get column sum value
     *
     * @param  string $column
     * @return \Framework\Database\Repository
     */
    public function sum(string $column): self
    {
        return $this->select(['SUM(' . $column . ') AS value']);
    }
    
    /**
     * get column max value
     *
     * @param  string $column
     * @return \Framework\Database\Repository
     */
    public function max(string $column): self
    {
        return $this->select(['MAX(' . $column . ') AS value']);
    }
    
    /**
     * get column min value
     *
     * @param  string $column
     * @return \Framework\Database\Repository
     */
    public function min(string $column): self
    {
        return $this->select(['MIN(' . $column . ') AS value']);
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
    public function metrics(string $column, string $type, string $trends, int $interval = 0, ?array $query = null): array
    {
        return (new Metrics($this->table))->getTrends($type, $column, $trends, $interval, $query);
    }
    
	/**
	 * add WHERE clause
	 *
	 * @param  string $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Repository
	 */
    public function where(string $column, $operator = null, $value = null): self
	{
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    $this->qb->whereColumn($column)->isNull();
                    break;

                case 'not null':
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

                case 'not in':
                    $this->qb->whereColumn($column)->notIn($value);
                    break;

                case 'like':
                    $this->qb->whereColumn($column)->like($value);
                    break;

                case 'not like':
                    $this->qb->whereColumn($column)->notLike($value);
                    break;

                default:
                    $this->qb->where($column, $operator, $value);
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
	 * @return \Framework\Database\Repository
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
     * whereRaw
     *
     * @param  string $query
     * @param  array $args
     * @return \Framework\Database\Repository
     */
    public function whereRaw(string $query, array $args = []): self
    {
        $this->qb->whereRaw($query, $args);
        return $this;
    }

	/**
	 * add AND clause
	 *
	 * @param  mixed $column
	 * @param  mixed $operator
	 * @param  mixed $value
	 * @return \Framework\Database\Repository
	 */
    public function and(string $column, $operator = null, $value = null): self
    {
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    $this->qb->andColumn($column)->isNull();
                    break;

                case 'not null':
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

                case 'not in':
                    $this->qb->andColumn($column)->notIn($value);
                    break;

                case 'like':
                    $this->qb->andColumn($column)->like($value);
                    break;

                case 'not like':
                    $this->qb->andColumn($column)->notLike($value);
                    break;

                default:
                    $this->qb->and($column, $operator, $value);
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
	 * @return \Framework\Database\Repository
	 */
    public function or(string $column, $operator = null, $value = null): self
    {
        if (!is_null($operator) && is_null($value)) {
            switch(strtolower($operator)) {
                case 'null':
                    $this->qb->orColumn($column)->isNull();
                    break;

                case 'not null':
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

                case 'not in':
                    $this->qb->orColumn($column)->notIn($value);
                    break;

                case 'like':
                    $this->qb->orColumn($column)->like($value);
                    break;

                case 'not like':
                    $this->qb->orColumn($column)->notLike($value);
                    break;

                default:
                    $this->qb->or($column, $operator, $value);
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
	 * @return \Framework\Database\Repository
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
     * @return \Framework\Database\Repository
     */
    public function havingRaw(string $query, array $args = []): self
    {
        $this->qb->havingRaw($query, $args);
        return $this;
    }
    
    /**
     * add WHERE BETWEEN clause for range
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Framework\Database\Repository
     */
    public function whereBetween(string $column, $start, $end): self
    {
        $this->qb->whereColumn($column)->between($start, $end);
        return $this;
    }
    
    /**
     * add WHERE NOT BETWEEN clause for range
     *
     * @param  string $column
     * @param  mixed $start
     * @param  mixed $end
     * @return \Framework\Database\Repository
     */
    public function whereNotBetween(string $column, $start, $end): self
    {
        $this->qb->whereColumn($column)->notBetween($start, $end);
        return $this;
    }
    
    /**
     * add SELECT BETWEEN query
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  array $columns 
     * @return \Framework\Database\Repository
     */
    public function between($start = null, $end = null, array $columns = ['*']): self
    {
        return $this->select($columns)->whereBetween('created_at', $start, $end);
    }
    
    /**
     * add SELECT NOT BETWEEN query
     *
     * @param  mixed $start
     * @param  mixed $end
     * @param  array $columns 
     * @return \Framework\Database\Repository
     */
    public function notBetween($start = null, $end = null, array $columns = ['*']): self
    {
        return $this->select($columns)->whereNotBetween('created_at', $start, $end);
    }

    /**
	 * add WHERE NULL clause
	 *
	 * @param  string $column
	 * @return \Framework\Database\Repository
	 */
    public function whereNull(string $column): self
	{
        $this->qb->whereColumn($column)->isNull();
		return $this;
	}

    /**
	 * add WHERE NOT NULL clause
	 *
	 * @param  string $column
	 * @return \Framework\Database\Repository
	 */
    public function whereNotNull(string $column): self
	{
        $this->qb->whereColumn($column)->notNull();
		return $this;
	}

    /**
	 * add WHERE LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\Database\Repository
	 */
    public function whereLike(string $column, $value): self
	{
        $this->qb->whereColumn($column)->like($value);
		return $this;
	}

    /**
	 * add WHERE NOT LIKE clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\Database\Repository
	 */
    public function whereNotLike(string $column, $value): self
	{
        $this->qb->whereColumn($column)->notLike($value);
		return $this;
	}

    /**
	 * add WHERE IN clause
	 *
	 * @param  string $column
	 * @param  array $values
	 * @return \Framework\Database\Repository
	 */
    public function whereIn(string $column, array $values): self
	{
        $this->qb->whereColumn($column)->in($values);
		return $this;
	}

    /**
	 * add WHERE NOT IN clause
	 *
	 * @param  string $column
	 * @param  mixed $value
	 * @return \Framework\Database\Repository
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
     * @return \Framework\Database\Repository
     */
    public function join(string $table, string $first_column, string $operator, string $second_column, string $method = 'inner'): self
    {
        $method = $method . 'Join';
        $this->qb->$method($table, $second_column, $operator, $first_column);
        return $this;
    }
    
    /**
     * add ORDER BY clause
     *
     * @param  string $column
     * @param  string $direction
     * @return \Framework\Database\Repository
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
     * @return \Framework\Database\Repository
     */
    public function orderDesc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'desc');
    }
    
    /**
     * add custom ORDER BY clause with ASC 
     *
     * @param  string $column
     * @return \Framework\Database\Repository
     */
    public function orderAsc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'asc');
    }
    
    /**
     * add custom ORDER BY created_at column clause with ASC
     *
     * @param  string $column
     * @return self
     */
    public function newest(string $column = 'created_at'): self
    {
        return $this->orderAsc($column);
    }
    
    /**
     * add custom ORDER BY created_at column clause with DESC
     *
     * @param  mixed $column
     * @return self
     */
    public function oldest(string $column = 'created_at'): self
    {
        return $this->orderDesc($column);
    }
    
    /**
     * add custom ORDER BY id column clause with ASC
     *
     * @param  string $column
     * @return self
     */
    public function earliest(string $column = 'id'): self
    {
        return $this->orderAsc($column);
    }
    
    /**
     * add custom ORDER BY id column clause with DESC
     *
     * @param  string $column
     * @return self
     */
    public function latest(string $column = 'id'): self
    {
        return $this->orderDesc($column);
    }
    
    /**
     * add GROUP clause
     *
     * @param  array $columns
     * @return \Framework\Database\Repository
     */
    public function group(array $columns): self
    {
        $this->qb->groupBy(implode(',', $columns));
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
     * @param  int $items_per_pages
     * @return \Framework\Support\Pager
     */
    public function paginate(int $items_per_pages): Pager
    {
        list($query, $args) = $this->qb->toSQL();

        $page = (new Request())->queries('page', 1);
        $total_items = count(QueryBuilder::setQuery($query, $args)->execute()->fetchAll());
        $pagination = generate_pagination($page, $total_items, $items_per_pages);
        
        $items = $items_per_pages > 0 ? 
            QueryBuilder::setQuery($query, $args)->limit($pagination['first_item'], $items_per_pages)->execute()->fetchAll() : 
            QueryBuilder::setQuery($query, $args)->execute()->fetchAll();
        
        return new Pager($items, $pagination);
    }
    
    /**
     * execute query
     *
     * @return \PDOStatement
     */
    public function persist(): \PDOStatement
    {
        return $this->qb->execute();
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
     * @param  mixed $query
     * @param  mixed $args
     * @return \Framework\Database\Repository
     */
    public function raw(string $query, array $args = []): self
    {
        $this->qb->rawQuery($query, $args);
        return $this;
    }
    
    /**
     * set query string and arguments
     *
     * @param  mixed $query
     * @param  mixed $args
     * @return \Framework\Database\Repository
     */
    public function query(string $query, array $args = []): self
    {
        $this->qb = QueryBuilder::setQuery($query, $args);
        return $this;
    }
    
    /**
     * generate sub query
     *
     * @param  mixed $callback
     * @return \Framework\Database\Repository
     */
    public function subQuery(callable $callback): self
    {
        call_user_func_array($callback, [$this]);
        return $this;
    }
}

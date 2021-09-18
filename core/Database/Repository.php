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
 * Manage database repositories
 */
class Repository
{
    private $table;

    /**
     * @var \Core\Database\QueryBuilder
     */
    protected $qb;
    
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function select(string ...$columns): self
    {
        $this->qb = QueryBuilder::table($this->table)->select(...$columns);
        return $this;
    }
    
    public function selectOne(string ...$columns)
    {
        return $this->select(...$columns)->get();
    }
    
    public function selectAll(string ...$columns)
    {
        return $this->select(...$columns)->getAll();
    }

    public function selectRaw(string $query, array $args = []): self
    {
        $this->qb = QueryBuilder::table($this->table)->selectRaw($query, $args);
        return $this;
    }
    
    public function findWhere(string $column, $operator = null, $value = null)
	{
        return $this->select('*')->where($column, $operator, $value)->get();
	}
    
    public function find($operator = null, $value = null)
	{
        return $this->findWhere('id', $operator, $value);
	}
    
    public function findAllWhere(string $column, $operator = null, $value = null)
	{
        return $this->select('*')->where($column, $operator, $value)->getAll();
	}
    
    public function findAll($operator = null, $value = null)
	{
        return $this->findAllWhere('id', $operator, $value);
	}
    
    public function findRaw(string $query, array $args = []): self
	{
        return $this->select('*')->whereRaw($query, $args);
	}

    public function findMany(array $items, string $glue = 'or'): self
    {
        $result = $this->select('*');
        $first = key($items);

        foreach ($items as $column => $value) {
            if ($items[$first] === $value) {
                $result->where($column, $value);
            } else {
                $result->$glue($column, $value);
            }
        }

        return $result;
    }
    
    /**
     * Retrieves data or throw exception if not found
     * 
     * @throws Exception
     */
    public function findOrFail(string $column, $operator = null, $value = null)
    {
        $result = $this->findWhere($column, $operator, $value);

        if ($result === false) {
            throw new Exception('Records not found in database.');
        }

        return $result;
    }
    
    /**
     * Insert data if item not found
     */
    public function findOrCreate(int $id, array $items)
    {
        try {
            $result = $this->findOrFail('id', $id);
        } catch (Exception $e) {
            $result = $this->insert($items);
        }

        return $result;
    }

    public function findBetween(string $column, $start = null, $end = null): self
    {
        return $this->select('*')->whereBetween($column, $start, $end)->get();
    }
    
    public function findAllBetween(string $column, $start = null, $end = null)
    {
        return $this->select('*')->whereBetween($column, $start, $end)->getAll();
    }
    
    public function findNotBetween(string $column, $start = null, $end = null): self
    {
        return $this->select('*')->whereNotBetween($column, $start, $end)->get();
    }
    
    public function findAllNotBetween(string $column, $start = null, $end = null)
    {
        return $this->select('*')->whereNotBetween($column, $start, $end)->getAll();
    }
    
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
    
    public function insert(array $items)
    {
        return QueryBuilder::table($this->table)->insert($items)->execute()->rowCount() > 0;
    }
    
    public function insertGetId(array $items)
    {
        if (!$this->insert($items)) {
            return null;
        }

        return QueryBuilder::lastInsertedId();
    }
    
    public function update(array $items): self
    {
        $this->qb = QueryBuilder::table($this->table)->update($items);
        return $this;
    }

    public function updateWhere(array $data, array $items)
    {
        if (!isset($data[2])) $data[2] = null;

        if (!$this->select('*')->where($data[0], $data[1], $data[2])->exists()) {
            return false;
        }

        return $this->update($items)->where($data[0], $data[1], $data[2])->execute()->rowCount() > 0;
    }
    
    public function updateIfExists(int $id, array $items)
    {
        return $this->updateWhere(['id', $id], $items);
    }
    
    public function updateOrCreate(int $id, array $items)
    {
        try {
            $this->findOrFail('id', $id);
            $result = $this->updateIfExists($id, $items);
        } catch (Exception $e) {
            $result = $this->insert($items);
        }

        return $result;
    }
    
    public function delete(): self
    {
        $this->qb = QueryBuilder::table($this->table)->delete();
        return $this;
    }
    
    public function deleteWhere(string $column, $operator = null, $value = null)
    {
        if (!$this->select('*')->where($column, $operator, $value)->exists()) {
            return false;
        }

        return $this->delete()->where($column, $operator, $value)->execute()->rowCount() > 0;
    }
    
    public function deleteIfExists(int $id)
    {
        return $this->deleteWhere('id', $id);
    }
    
    public function count(string $column = 'id'): self
    {
        return $this->select('COUNT(' . $column . ') AS value');
    }

    public function sum(string $column): self
    {
        return $this->select('SUM(' . $column . ') AS value');
    }
    
    public function max(string $column): self
    {
        return $this->select('MAX(' . $column . ') AS value');
    }
    
    public function min(string $column): self
    {
        return $this->select('MIN(' . $column . ') AS value');
    }
    
    public function metrics(string $column, string $type, string $period, int $interval = 0, ?array $query = null)
    {
        return (new Metrics($this->table))->get($column, $type, $period, $interval, $query);
    }

    public function trends(string $column, string $type, string $period, int $interval = 0, ?array $query = null)
    {
        return (new Metrics($this->table))->getTrends($column, $type, $period, $interval, $query);
    }
    
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

        elseif (!is_null($operator) && !is_null($value)) {
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

        elseif (!is_null($operator) && !is_null($value)) {
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

        elseif (!is_null($operator) && !is_null($value)) {
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

    public function whereNot(string $column, $operator = null, $value = null): self
	{
        if (is_null($operator) && !is_null($value)) {
            $this->qb->whereNot($column, $value);
        } else {
            $this->qb->whereNot($column, $operator, $value);
        }

		return $this;
    }
    
    public function whereGreater(string $column, $value): self
    {
        return $this->where($column, '>=', $value);
    }
    
    public function andGreater(string $column, $value): self
    {
        return $this->and($column, '>=', $value);
    }
    
    public function orGreater(string $column, $value): self
    {
        return $this->or($column, '>=', $value);
    }
        
    public function whereLower(string $column, $value): self
    {
        return $this->where($column, '<=', $value);
    }
        
    public function andLower(string $column, $value): self
    {
        return $this->and($column, '<=', $value);
    }
    
    public function orLower(string $column, $value): self
    {
        return $this->or($column, '<=', $value);
    }
    
    public function whereRaw(string $query, array $args = []): self
    {
        $this->qb->whereRaw($query, $args);
        return $this;
    }
    
    public function andRaw(string $query, array $args = []): self
    {
        $this->qb->andRaw($query, $args);
        return $this;
    }
        
    public function orRaw(string $query, array $args = []): self
    {
        $this->qb->orRaw($query, $args);
        return $this;
    }

    public function having(string $column, $operator = null, $value = null): self
	{
        if (is_null($operator) && !is_null($value)) {
            $this->qb->having($column, $value);
        } else {
            $this->qb->having($column, $operator, $value);
        }

		return $this;
	}
    
    public function havingRaw(string $query, array $args = []): self
    {
        $this->qb->havingRaw($query, $args);
        return $this;
    }
    
    public function whereBetween(string $column, $start, $end): self
    {
        $this->qb->whereColumn($column)->between($start, $end);
        return $this;
    }
    
    public function whereNotBetween(string $column, $start, $end): self
    {
        $this->qb->whereColumn($column)->notBetween($start, $end);
        return $this;
    }
    
    public function whereNull(string $column): self
	{
        $this->qb->whereColumn($column)->isNull();
		return $this;
	}

    public function whereNotNull(string $column): self
	{
        $this->qb->whereColumn($column)->notNull();
		return $this;
	}

    public function whereLike(string $column, $value): self
	{
        $this->qb->whereColumn($column)->like($value);
		return $this;
	}

    public function whereNotLike(string $column, $value): self
	{
        $this->qb->whereColumn($column)->notLike($value);
		return $this;
	}

    public function whereIn(string $column, array $values): self
	{
        $this->qb->whereColumn($column)->in($values);
		return $this;
	}

    public function whereNotIn(string $column, $value): self
	{
        $this->qb->whereColumn($column)->notIn($value);
		return $this;
	}

    public function join(string $table, string $first_column, string $operator, string $second_column, string $method = 'inner'): self
    {
        $method = $method . 'Join';
        $this->qb->$method($table, $second_column, $operator, $first_column);
        return $this;
    }
    
    public function orderBy(string $column, string $direction): self
    {
        $this->qb->orderBy($column, $direction);
        return $this;
    }
    
    public function orderDesc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'desc');
    }
    
    public function orderAsc(string $column = 'id'): self
    {
        return $this->orderBy($column, 'asc');
    }
    
    public function newest(string $column = 'created_at'): self
    {
        return $this->orderDesc($column);
    }
    
    public function oldest(string $column = 'created_at'): self
    {
        return $this->orderAsc($column);
    }
    
    public function latest(string $column = 'id'): self
    {
        return $this->orderDesc($column);
    }
    
    public function groupBy(string ...$columns): self
    {
        $this->qb->groupBy(...$columns);
        return $this;
    }

    public function exists()
    {
        return $this->qb->exists();
    }
    
    public function first()
    {
        return $this->getAll()[0];
    }
    
    public function last()
    {
        $rows = $this->getAll();
        return end($rows);
    }

    public function range(int $start, int $end)
    {
        $this->qb->limit($start, $end);
        return $this->getAll();
    }
    
    public function take(int $count)
    {
        return $this->range(0, $count);
    }

    public function paginate(int $items_per_page, int $page = 1): Pager
    {
        list($query, $args) = $this->qb->toSQL();

        $total_items = count(QueryBuilder::setQuery($query, $args)->fetchAll());
        $pager = new Pager($total_items, $items_per_page, $page);
        
        $items = $items_per_page > 0 
            ? QueryBuilder::setQuery($query, $args)->limit($pager->getFirstItem(), $items_per_page)->fetchAll() 
            : QueryBuilder::setQuery($query, $args)->fetchAll();
        
        return $pager->setItems($items);
    }
    
    public function get()
    {
        return $this->execute()->fetch();
    }
    
    public function getAll()
    {
        return $this->execute()->fetchAll();
    }
    
    public function toSQL()
    {
        return $this->qb->toSQL();
    }

    public function rawQuery(string $query, array $args = []): self
    {
        $this->qb->rawQuery($query, $args);
        return $this;
    }
    
    public function setQuery(string $query, array $args = []): self
    {
        $this->qb = QueryBuilder::setQuery($query, $args);
        return $this;
    }
    
    public function subQuery($callback): self
    {
        if (!is_null($callback)) {
            call_user_func_array($callback, [$this]);
        }

        return $this;
    }
    
    public function execute()
    {
        return $this->qb->execute();
    }
}

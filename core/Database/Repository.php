<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use Closure;
use Core\Exceptions\InvalidSQLQueryException;
use Core\Support\Metrics\Metrics;
use Core\Support\Pager;
use PDOStatement;

/**
 * Manage database repositories
 */
class Repository
{
    protected QueryBuilder $qb;

    public function __construct(protected readonly string $table) {}

    public function select(array|string $columns): self
    {
        $this->qb = QueryBuilder::table($this->table)->select($columns);
        return $this;
    }
    
    public function selectOne(array|string $columns): Model|false
    {
        return $this->select($columns)->get();
    }
    
    public function selectAll(array|string $columns): array|false
    {
        return $this->select($columns)->getAll();
    }

    public function selectRaw(string $query, array $args = []): self
    {
        $this->qb = QueryBuilder::table($this->table)->selectRaw($query, $args);
        return $this;
    }
    
    public function findWhere(string $column, $operator = null, $value = null): Model|false
	{
        return $this->select('*')->where($column, $operator, $value)->get();
	}
    
    public function find($operator = null, $value = null): Model|false
	{
        return $this->findWhere('id', $operator, $value);
	}
    
    public function findAllWhere(string $column, $operator = null, $value = null): array|false
	{
        return $this->select('*')->where($column, $operator, $value)->getAll();
	}
    
    public function findAll($operator = null, $value = null): array|false
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
    
    public function findOrCreate(int $id, array $items): Model|bool
    {
        $result = $this->findWhere('id', $id);

        if ($result === false) {
            $result = $this->insert($items);
        }

        return $result;
    }

    public function findBetween(string $column, $start = null, $end = null): Model|false
    {
        return $this->select('*')->whereBetween($column, $start, $end)->get();
    }
    
    public function findAllBetween(string $column, $start = null, $end = null): array|false
    {
        return $this->select('*')->whereBetween($column, $start, $end)->getAll();
    }
    
    public function findNotBetween(string $column, $start = null, $end = null): Model|false
    {
        return $this->select('*')->whereNotBetween($column, $start, $end)->get();
    }
    
    public function findAllNotBetween(string $column, $start = null, $end = null): array|false
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
    
    public function insert(array $items): bool
    {
        return QueryBuilder::table($this->table)->insert($items)->execute()->rowCount() > 0;
    }
    
    public function insertGetId(array $items): int|null
    {
        return !$this->insert($items) ? null : QueryBuilder::lastInsertedId();
    }
    
    public function update(array $items): self
    {
        $this->qb = QueryBuilder::table($this->table)->update($items);
        return $this;
    }

    public function updateWhere(array $data, array $items): bool
    {
        if (!isset($data[2])) {
            $data[2] = null;
        }

        if (!$this->select('*')->where($data[0], $data[1], $data[2])->exists()) {
            return false;
        }

        return $this->update($items)->where($data[0], $data[1], $data[2])->execute()->rowCount() > 0;
    }
    
    public function updateIfExists(int $id, array $items):bool
    {
        return $this->updateWhere(['id', $id], $items);
    }
    
    public function updateOrCreate(int $id, array $items): bool
    {
        if ($this->findWhere('id', $id) === false) {
            return $this->insert($items);
        }

        return $this->updateIfExists($id, $items);
    }
    
    public function delete(): self
    {
        $this->qb = QueryBuilder::table($this->table)->delete();
        return $this;
    }
    
    public function deleteWhere(string $column, $operator = null, $value = null): bool
    {
        if (!$this->select('*')->where($column, $operator, $value)->exists()) {
            return false;
        }

        return $this->delete()->where($column, $operator, $value)->execute()->rowCount() > 0;
    }
    
    public function deleteIfExists(int $id): bool
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
    
    public function metrics(): Metrics
    {
        return Metrics::table($this->table);
    }

    public function where(string $column, $operator = null, $value = null): self
	{
        if (is_null($operator) && is_null($value)) {
            throw new InvalidSQLQueryException();
        }

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
        if (is_null($operator) && is_null($value)) {
            throw new InvalidSQLQueryException();
        }

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
        if (is_null($operator) && is_null($value)) {
            throw new InvalidSQLQueryException();
        }

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

    public function groupBy(array|string $columns): self
    {
        $this->qb->groupBy($columns);
        return $this;
    }

    public function exists(): bool
    {
        return $this->qb->exists();
    }
    
    public function first(): Model|false
    {
        $rows = $this->oldest()->take(1);
        return !$rows ? false : $rows[0];
    }
    
    public function last(): Model|false
    {
        $rows = $this->newest()->take(1);
        return !$rows ? false : $rows[0];
    }

    public function range(int $start, int $end): array|false
    {
        $this->qb->limit($start, $end);
        return $this->getAll();
    }
    
    public function take(int $count): array|false
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
    
    public function get(): Model|false
    {
        $row = $this->execute()->fetch();
        return !$row ? false : new Model($this->table, (array) $row);
    }
    
    public function getAll(): array|false
    {
        $rows = $this->execute()->fetchAll();
        return !$rows ? false : array_map(fn ($row) => new Model($this->table, (array) $row), $rows);
    }
    
    public function toSQL(): array
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

    public function subQuery(Closure $callback): self
    {
        call_user_func_array($callback, [$this]);
        return $this;
    }

    public function subQueryWhen(bool $condition, Closure $callback): self
    {
        if ($condition) {
            call_user_func_array($callback, [$this]);
        }

        return $this;
    }

    public function execute(): false|PDOStatement
    {
        return $this->qb->execute();
    }

    public function dd(): void
    {
        dd($this->toSQL());
    }
}

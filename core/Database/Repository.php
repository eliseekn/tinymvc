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
use Core\Database\Metrics\Metrics;
use Core\Enums\JoinMethod;
use Core\Exceptions\InvalidSQLQueryException;
use Core\Support\Pagination;
use PDOStatement;

/**
 * Manage database repositories.
 */
class Repository
{
    protected QueryBuilder $qb;

    public function __construct(protected readonly string $table) {}

    public function getTable(): string
    {
        return $this->table;
    }

    public function select(array|string $columns): self
    {
        $this->qb = QueryBuilder::table($this->table)->select($columns);

        return $this;
    }

    public function addSelect(array|string $columns): self
    {
        $this->qb->addSelect($columns);

        return $this;
    }

    public function addWhere(string $column, $operator = null, $value = null): self
    {
        $this->qb->addWhere($column, $operator, $value);

        return $this;
    }

    public function selectOne(array|string $columns): ?Model
    {
        return $this->select($columns)->get();
    }

    public function selectAll(array|string $columns): array
    {
        return $this->select($columns)->getAll();
    }

    public function selectRaw(string $query): self
    {
        $this->qb = QueryBuilder::table($this->table)->selectRaw($query);

        return $this;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function findWhere(string $column, $operator = null, $value = null): ?Model
    {
        return $this->select('*')->where($column, $operator, $value)->get();
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function find($operator = null, $value = null): ?Model
    {
        return $this->findWhere('id', $operator, $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function findAllWhere(string $column, $operator = null, $value = null): array
    {
        return $this->select('*')->where($column, $operator, $value)->getAll();
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function findAll($operator = null, $value = null): array
    {
        return $this->findAllWhere('id', $operator, $value);
    }

    public function findRaw(string $query, array $args = []): self
    {
        return $this->select('*')->whereRaw($query, $args);
    }

    /**
     * @throws InvalidSQLQueryException
     */
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
     * @throws InvalidSQLQueryException
     */
    public function findOrCreate(string $column, $value, array $items): Model|bool
    {
        $result = $this->findWhere($column, $value);

        if (! $result) {
            $result = $this->insertGetId($items);
            $result = $this->find($result);
        }

        return $result;
    }

    public function findBetween(string $column, $start = null, $end = null): ?Model
    {
        return $this->select('*')->whereBetween($column, $start, $end)->get();
    }

    public function findAllBetween(string $column, $start = null, $end = null): array
    {
        return $this->select('*')->whereBetween($column, $start, $end)->getAll();
    }

    public function findNotBetween(string $column, $start = null, $end = null): ?Model
    {
        return $this->select('*')->whereNotBetween($column, $start, $end)->get();
    }

    public function findAllNotBetween(string $column, $start = null, $end = null): array
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

    public function insertGetId(array $items): ?int
    {
        if (! $this->insert($items)) {
            return null;
        }

        $id = Connection::getInstance()->lastInsertedId($this->getTable());

        return (int) $id ?: null;
    }

    public function update(array $items): self
    {
        $this->qb = QueryBuilder::table($this->table)->update($items);

        return $this;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function updateWhere(array $data, array $items): bool
    {
        if (! isset($data[2])) {
            $data[2] = null;
        }

        if (! $this->select('*')->where($data[0], $data[1], $data[2])->exists()) {
            return false;
        }

        return $this->update($items)->where($data[0], $data[1], $data[2])->execute()->rowCount() > 0;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function updateIfExists(int $id, array $items): bool
    {
        return $this->updateWhere(['id', $id], $items);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function updateOrCreate(int $id, array $items): bool
    {
        if (! $this->findWhere('id', $id)) {
            return $this->insert($items);
        }

        return $this->updateIfExists($id, $items);
    }

    public function delete(): self
    {
        $this->qb = QueryBuilder::table($this->table)->delete();

        return $this;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function deleteWhere(string $column, $operator = null, $value = null): bool
    {
        if (! $this->select('*')->where($column, $operator, $value)->exists()) {
            return false;
        }

        return $this->delete()->where($column, $operator, $value)->execute()->rowCount() > 0;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function deleteIfExists(int $id): bool
    {
        return $this->deleteWhere('id', $id);
    }

    public function count(string $column = 'id'): self
    {
        return $this->select('COUNT('.$column.') AS value');
    }

    public function sum(string $column): self
    {
        return $this->select('SUM('.$column.') AS value');
    }

    public function average(string $column): self
    {
        return $this->select('AVG('.$column.') AS value');
    }

    public function max(string $column): self
    {
        return $this->select('MAX('.$column.') AS value');
    }

    public function min(string $column): self
    {
        return $this->select('MIN('.$column.') AS value');
    }

    public function metrics(): Metrics
    {
        return Metrics::table($this->table);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function where(string $column, $operator = null, $value = null): self
    {
        if (is_null($operator) && is_null($value)) {
            throw new InvalidSQLQueryException;
        }

        if (! is_null($operator) && is_null($value)) {
            if (! is_string($operator)) {
                $this->qb->where($column, $operator);
            } else {
                match (strtolower($operator)) {
                    'null' => $this->qb->whereColumn($column)->isNull(),
                    '!null' => $this->qb->whereColumn($column)->notNull(),
                    default => $this->qb->where($column, $operator),
                };
            }
        } elseif (! is_null($operator) && ! is_null($value)) {
            match (strtolower($operator)) {
                'in' => $this->qb->whereColumn($column)->in($value),
                '!in' => $this->qb->whereColumn($column)->notIn($value),
                'like' => $this->qb->whereColumn($column)->like($value),
                '!like' => $this->qb->whereColumn($column)->notLike($value),
                'between' => is_array($value) ? $this->qb->whereColumn($column)->between($value[0], $value[1]) : null,
                '!between' => is_array($value) ? $this->qb->whereColumn($column)->notBetween($value[0], $value[1]) : null,
                default => $this->qb->where($column, $operator, $value),
            };
        }

        return $this;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function and(string $column, $operator = null, $value = null): self
    {
        if (is_null($operator) && is_null($value)) {
            throw new InvalidSQLQueryException;
        }

        if (! is_null($operator) && is_null($value)) {
            if (! is_string($operator)) {
                $this->qb->and($column, $operator);
            } else {
                match (strtolower($operator)) {
                    'null' => $this->qb->andColumn($column)->isNull(),
                    '!null' => $this->qb->andColumn($column)->notNull(),
                    default => $this->qb->and($column, $operator),
                };
            }
        } elseif (! is_null($operator) && ! is_null($value)) {
            match (strtolower($operator)) {
                'in' => $this->qb->andColumn($column)->in($value),
                '!in' => $this->qb->andColumn($column)->notIn($value),
                'like' => $this->qb->andColumn($column)->like($value),
                '!like' => $this->qb->andColumn($column)->notLike($value),
                'between' => is_array($value) ? $this->qb->andColumn($column)->between($value[0], $value[1]) : null,
                '!between' => is_array($value) ? $this->qb->andColumn($column)->notBetween($value[0], $value[1]) : null,
                default => $this->qb->and($column, $operator, $value),
            };
        }

        return $this;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function or(string $column, $operator = null, $value = null): self
    {
        if (is_null($operator) && is_null($value)) {
            throw new InvalidSQLQueryException;
        }

        if (! is_null($operator) && is_null($value)) {
            if (! is_string($operator)) {
                $this->qb->or($column, $operator);
            } else {
                match (strtolower($operator)) {
                    'null' => $this->qb->orColumn($column)->isNull(),
                    '!null' => $this->qb->orColumn($column)->notNull(),
                    default => $this->qb->or($column, $operator),
                };
            }
        } elseif (! is_null($operator) && ! is_null($value)) {
            match (strtolower($operator)) {
                'in' => $this->qb->orColumn($column)->in($value),
                '!in' => $this->qb->orColumn($column)->notIn($value),
                'like' => $this->qb->orColumn($column)->like($value),
                '!like' => $this->qb->orColumn($column)->notLike($value),
                'between' => is_array($value) ? $this->qb->orColumn($column)->between($value[0], $value[1]) : null,
                '!between' => is_array($value) ? $this->qb->orColumn($column)->notBetween($value[0], $value[1]) : null,
                default => $this->qb->or($column, $operator, $value),
            };
        }

        return $this;
    }

    public function whereNotEquals(string $column, $value): self
    {
        $this->qb->where($column, '<>', $value);

        return $this;
    }

    public function andNotEquals(string $column, $value): self
    {
        $this->qb->and($column, '<>', $value);

        return $this;
    }

    public function orNotEquals(string $column, $value): self
    {
        $this->qb->or($column, '<>', $value);

        return $this;
    }

    public function whereNot(string $query, array $args = []): self
    {
        $this->qb->whereNot($query, $args);

        return $this;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function whereGreater(string $column, $value): self
    {
        return $this->where($column, '>', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function whereGreaterOrEqual(string $column, $value): self
    {
        return $this->where($column, '>=', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function andGreater(string $column, $value): self
    {
        return $this->and($column, '>', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function andGreaterOrEqual(string $column, $value): self
    {
        return $this->and($column, '>=', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function orGreater(string $column, $value): self
    {
        return $this->or($column, '>', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function orGreaterOrEqual(string $column, $value): self
    {
        return $this->and($column, '>=', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function whereLower(string $column, $value): self
    {
        return $this->where($column, '<', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function whereLowerOrEqual(string $column, $value): self
    {
        return $this->where($column, '<=', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function andLower(string $column, $value): self
    {
        return $this->and($column, '<', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function andLowerOrEqual(string $column, $value): self
    {
        return $this->and($column, '<=', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function orLower(string $column, $value): self
    {
        return $this->or($column, '<', $value);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function orLowerOrEqual(string $column, $value): self
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
        if (is_null($operator) && ! is_null($value)) {
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

    public function join(string $table, string $first_column, string $operator, string $second_column, string $method = JoinMethod::INNER): self
    {
        $this->qb->join($table, $second_column, $operator, $first_column, $method);

        return $this;
    }

    public function addJoin(string $table, string $first_column, string $operator, string $second_column, string $method = JoinMethod::INNER): self
    {
        $this->qb->addJoin($table, $first_column, $operator, $second_column.$method);

        return $this;
    }

    public function crossJoin(string $table): self
    {
        $this->qb->crossJoin($table);

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

    public function notExists(): bool
    {
        return $this->qb->notExists();
    }

    public function first(): ?Model
    {
        $rows = $this->oldest()->take(1);

        return ! $rows ? null : $rows[0];
    }

    public function last(): ?Model
    {
        $rows = $this->newest()->take(1);

        return ! $rows ? null : $rows[0];
    }

    public function take(int $count): array
    {
        $this->qb->limit($count);

        return $this->getAll();
    }

    public function paginate(int $perPage, int $page = 1): Pagination
    {
        [$query, $args] = $this->qb->toSQL();

        $countQuery = preg_replace('/\s+(ORDER BY|LIMIT|OFFSET)\s+.+$/i', '', $query);
        $countQuery = "SELECT COUNT(*) as total FROM ({$countQuery}) as count_wrapper";
        $totalItems = (int) QueryBuilder::setQuery($countQuery, $args)->fetch('total');

        $pager = new Pagination($totalItems, $perPage, $page);

        $items = $perPage > 0
            ? QueryBuilder::setQuery($query, $args)->limit($perPage, $pager->getFirstItem())->fetchAll()
            : QueryBuilder::setQuery($query, $args)->fetchAll();

        $items = array_map(fn ($item) => new Model($this->table, (array) $item), $items);

        return $pager->setItems($items);
    }

    public function get(): ?Model
    {
        $row = $this->execute()->fetch();

        return ! $row ? null : new Model($this->table, (array) $row);
    }

    public function getAll(): array
    {
        $rows = $this->execute()->fetchAll();

        return ! $rows ? [] : array_map(fn ($row) => new Model($this->table, (array) $row), $rows);
    }

    public function chunk(int $count, Closure $callback): void
    {
        [$query, $args] = $this->qb->toSQL();

        $offset = 0;

        while (true) {
            $rows = QueryBuilder::setQuery($query, $args)
                ->limit($count, $offset)
                ->fetchAll();

            if (empty($rows)) {
                break;
            }

            foreach ($rows as $row) {
                $callback(new Model($this->table, (array) $row));
            }

            $offset += $count;
        }
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

    public function when(bool $condition, ?Closure $callback = null): self
    {
        if ($condition) {
            $this->subQuery($callback);
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

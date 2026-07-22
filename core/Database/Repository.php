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
use Core\Exceptions\CoreException;
use Core\Support\Pagination;
use PDOStatement;

/**
 * Manage database repositories.
 */
class Repository
{
    protected QueryBuilder $query;

    public function __construct(protected readonly string $table, protected readonly string $modelClass = Model::class)
    {
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function select(array|string $columns): self
    {
        $this->query = QueryBuilder::table($this->table)->select($columns);

        return $this;
    }

    public function addSelect(array|string $columns): self
    {
        $this->query->addSelect($columns);

        return $this;
    }

    public function addWhere(string $column, $operator = null, $value = null): self
    {
        $this->query->addWhere($column, $operator, $value);

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
        $this->query = QueryBuilder::table($this->table)->selectRaw($query);

        return $this;
    }

    public function findWhere(string $column, $operator = null, $value = null): ?Model
    {
        return $this->select('*')->where($column, $operator, $value)->get();
    }

    public function find($operator = null, $value = null): ?Model
    {
        return $this->findWhere('id', $operator, $value);
    }

    public function findAllWhere(string $column, $operator = null, $value = null): array
    {
        return $this->select('*')->where($column, $operator, $value)->getAll();
    }

    public function findAll($operator = null, $value = null): array
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

    public function findOrCreate(string $column, mixed $value, array $items): Model|bool
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

        $id = Connection::getInstance()->lastInsertedId($this->table);

        return (int) $id ?: null;
    }

    public function update(array $items): self
    {
        $this->query = QueryBuilder::table($this->table)->update($items);

        return $this;
    }

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

    public function updateIfExists(int $id, array $items): bool
    {
        return $this->updateWhere(['id', $id], $items);
    }

    public function updateOrCreate(int $id, array $items): bool
    {
        if (! $this->findWhere('id', $id)) {
            return $this->insert($items);
        }

        return $this->updateIfExists($id, $items);
    }

    public function delete(): self
    {
        $this->query = QueryBuilder::table($this->table)->delete();

        return $this;
    }

    public function deleteWhere(string $column, $operator = null, $value = null): bool
    {
        if (! $this->select('*')->where($column, $operator, $value)->exists()) {
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

    public function where(string $column, $operator = null, $value = null): self
    {
        if (is_null($operator) && is_null($value)) {
            throw new CoreException('Invalid SQL query');
        }

        if (! is_null($operator) && is_null($value)) {
            if (! is_string($operator)) {
                $this->query->where($column, $operator);
            } else {
                match (strtolower($operator)) {
                    'null' => $this->query->whereColumn($column)->isNull(),
                    '!null' => $this->query->whereColumn($column)->notNull(),
                    default => $this->query->where($column, $operator),
                };
            }
        } elseif (! is_null($operator) && ! is_null($value)) {
            match (strtolower($operator)) {
                'in' => $this->query->whereColumn($column)->in($value),
                '!in' => $this->query->whereColumn($column)->notIn($value),
                'like' => $this->query->whereColumn($column)->like($value),
                '!like' => $this->query->whereColumn($column)->notLike($value),
                'between' => is_array($value) ? $this->query->whereColumn($column)->between($value[0], $value[1]) : null,
                '!between' => is_array($value) ? $this->query->whereColumn($column)->notBetween($value[0], $value[1]) : null,
                default => $this->query->where($column, $operator, $value),
            };
        }

        return $this;
    }

    public function and(string $column, $operator = null, $value = null): self
    {
        if (is_null($operator) && is_null($value)) {
            throw new CoreException('Invalid SQL query');
        }

        if (! is_null($operator) && is_null($value)) {
            if (! is_string($operator)) {
                $this->query->and($column, $operator);
            } else {
                match (strtolower($operator)) {
                    'null' => $this->query->andColumn($column)->isNull(),
                    '!null' => $this->query->andColumn($column)->notNull(),
                    default => $this->query->and($column, $operator),
                };
            }
        } elseif (! is_null($operator) && ! is_null($value)) {
            match (strtolower($operator)) {
                'in' => $this->query->andColumn($column)->in($value),
                '!in' => $this->query->andColumn($column)->notIn($value),
                'like' => $this->query->andColumn($column)->like($value),
                '!like' => $this->query->andColumn($column)->notLike($value),
                'between' => is_array($value) ? $this->query->andColumn($column)->between($value[0], $value[1]) : null,
                '!between' => is_array($value) ? $this->query->andColumn($column)->notBetween($value[0], $value[1]) : null,
                default => $this->query->and($column, $operator, $value),
            };
        }

        return $this;
    }

    public function or(string $column, $operator = null, $value = null): self
    {
        if (is_null($operator) && is_null($value)) {
            throw new CoreException('Invalid SQL query');
        }

        if (! is_null($operator) && is_null($value)) {
            if (! is_string($operator)) {
                $this->query->or($column, $operator);
            } else {
                match (strtolower($operator)) {
                    'null' => $this->query->orColumn($column)->isNull(),
                    '!null' => $this->query->orColumn($column)->notNull(),
                    default => $this->query->or($column, $operator),
                };
            }
        } elseif (! is_null($operator) && ! is_null($value)) {
            match (strtolower($operator)) {
                'in' => $this->query->orColumn($column)->in($value),
                '!in' => $this->query->orColumn($column)->notIn($value),
                'like' => $this->query->orColumn($column)->like($value),
                '!like' => $this->query->orColumn($column)->notLike($value),
                'between' => is_array($value) ? $this->query->orColumn($column)->between($value[0], $value[1]) : null,
                '!between' => is_array($value) ? $this->query->orColumn($column)->notBetween($value[0], $value[1]) : null,
                default => $this->query->or($column, $operator, $value),
            };
        }

        return $this;
    }

    public function whereNotEquals(string $column, mixed $value): self
    {
        $this->query->where($column, '<>', $value);

        return $this;
    }

    public function andNotEquals(string $column, mixed $value): self
    {
        $this->query->and($column, '<>', $value);

        return $this;
    }

    public function orNotEquals(string $column, mixed $value): self
    {
        $this->query->or($column, '<>', $value);

        return $this;
    }

    public function whereNot(string $query, array $args = []): self
    {
        $this->query->whereNot($query, $args);

        return $this;
    }

    public function whereGreater(string $column, mixed $value): self
    {
        return $this->where($column, '>', $value);
    }

    public function whereGreaterOrEqual(string $column, mixed $value): self
    {
        return $this->where($column, '>=', $value);
    }

    public function andGreater(string $column, mixed $value): self
    {
        return $this->and($column, '>', $value);
    }

    public function andGreaterOrEqual(string $column, mixed $value): self
    {
        return $this->and($column, '>=', $value);
    }

    public function orGreater(string $column, mixed $value): self
    {
        return $this->or($column, '>', $value);
    }

    public function orGreaterOrEqual(string $column, mixed $value): self
    {
        return $this->and($column, '>=', $value);
    }

    public function whereLower(string $column, mixed $value): self
    {
        return $this->where($column, '<', $value);
    }

    public function whereLowerOrEqual(string $column, mixed $value): self
    {
        return $this->where($column, '<=', $value);
    }

    public function andLower(string $column, mixed $value): self
    {
        return $this->and($column, '<', $value);
    }

    public function andLowerOrEqual(string $column, mixed $value): self
    {
        return $this->and($column, '<=', $value);
    }

    public function orLower(string $column, mixed $value): self
    {
        return $this->or($column, '<', $value);
    }

    public function orLowerOrEqual(string $column, mixed $value): self
    {
        return $this->or($column, '<=', $value);
    }

    public function whereRaw(string $query, array $args = []): self
    {
        $this->query->whereRaw($query, $args);

        return $this;
    }

    public function andRaw(string $query, array $args = []): self
    {
        $this->query->andRaw($query, $args);

        return $this;
    }

    public function orRaw(string $query, array $args = []): self
    {
        $this->query->orRaw($query, $args);

        return $this;
    }

    public function having(string $column, $operator = null, $value = null): self
    {
        if (is_null($operator) && ! is_null($value)) {
            $this->query->having($column, $value);
        } else {
            $this->query->having($column, $operator, $value);
        }

        return $this;
    }

    public function havingRaw(string $query, array $args = []): self
    {
        $this->query->havingRaw($query, $args);

        return $this;
    }

    public function whereBetween(string $column, mixed $start, mixed $end): self
    {
        $this->query->whereColumn($column)->between($start, $end);

        return $this;
    }

    public function whereNotBetween(string $column, mixed $start, mixed $end): self
    {
        $this->query->whereColumn($column)->notBetween($start, $end);

        return $this;
    }

    public function whereNull(string $column): self
    {
        $this->query->whereColumn($column)->isNull();

        return $this;
    }

    public function whereNotNull(string $column): self
    {
        $this->query->whereColumn($column)->notNull();

        return $this;
    }

    public function whereLike(string $column, mixed $value): self
    {
        $this->query->whereColumn($column)->like($value);

        return $this;
    }

    public function whereNotLike(string $column, mixed $value): self
    {
        $this->query->whereColumn($column)->notLike($value);

        return $this;
    }

    public function whereIn(string $column, array $values): self
    {
        $this->query->whereColumn($column)->in($values);

        return $this;
    }

    public function whereNotIn(string $column, mixed $value): self
    {
        $this->query->whereColumn($column)->notIn($value);

        return $this;
    }

    public function join(string $table, string $first_column, string $operator, string $second_column, string $method = JoinMethod::INNER): self
    {
        $this->query->join($table, $second_column, $operator, $first_column, $method);

        return $this;
    }

    public function addJoin(string $table, string $first_column, string $operator, string $second_column, string $method = JoinMethod::INNER): self
    {
        $this->query->addJoin($table, $first_column, $operator, $second_column.$method);

        return $this;
    }

    public function crossJoin(string $table): self
    {
        $this->query->crossJoin($table);

        return $this;
    }

    public function orderBy(array|string $columns, string $direction): self
    {
        $this->query->orderBy($columns, $direction);

        return $this;
    }

    public function orderDesc(array|string $columns = 'id'): self
    {
        return $this->orderBy($columns, 'desc');
    }

    public function orderAsc(array|string $columns = 'id'): self
    {
        return $this->orderBy($columns, 'asc');
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
        $this->query->groupBy($columns);

        return $this;
    }

    public function exists(): bool
    {
        return $this->query->exists();
    }

    public function notExists(): bool
    {
        return $this->query->notExists();
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
        $this->query->limit($count);

        return $this->getAll();
    }

    public function limit(int $count, ?int $offset = null): self
    {
        $this->query->limit($count, $offset);

        return $this;
    }

    public function paginate(int $perPage, int $page = 1): Pagination
    {
        [$query, $args] = $this->query->toSQL();

        $countQuery = preg_replace('/\s+(ORDER BY|LIMIT|OFFSET)\s+.+$/i', '', $query);
        $countQuery = "SELECT COUNT(*) as total FROM ({$countQuery}) as count_wrapper";
        $totalItems = (int) QueryBuilder::setQuery($countQuery, $args)->fetch('total');

        $pagination = new Pagination($totalItems, $perPage, $page);

        $items = $perPage > 0
            ? QueryBuilder::setQuery($query, $args)->limit($perPage, $pagination->getFirstItem())->fetchAll()
            : QueryBuilder::setQuery($query, $args)->fetchAll();

        $items = array_map(fn ($item) => $this->newModel((array) $item), $items);

        return $pagination->setItems($items);
    }

    public function get(): ?Model
    {
        $row = $this->execute()->fetch();

        return ! $row ? null : $this->newModel((array) $row);
    }

    public function getAll(): array
    {
        $rows = $this->execute()->fetchAll();

        return ! $rows ? [] : array_map(fn ($row) => $this->newModel((array) $row), $rows);
    }

    public function chunk(int $count, Closure $callback): void
    {
        [$query, $args] = $this->query->toSQL();

        $offset = 0;

        while (true) {
            $rows = QueryBuilder::setQuery($query, $args)
                ->limit($count, $offset)
                ->fetchAll();

            if (empty($rows)) {
                break;
            }

            foreach ($rows as $row) {
                $callback($this->newModel((array) $row));
            }

            $offset += $count;
        }
    }

    protected function newModel(array $attributes): Model
    {
        return new $this->modelClass($this->table, $attributes);
    }

    public function toSQL(): array
    {
        return $this->query->toSQL();
    }

    public function rawQuery(string $query, array $args = []): self
    {
        $this->query->rawQuery($query, $args);

        return $this;
    }

    public function setQuery(string $query, array $args = []): self
    {
        $this->query = QueryBuilder::setQuery($query, $args);

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
        return $this->query->execute();
    }

    public function dd(): void
    {
        dd($this->toSQL());
    }
}

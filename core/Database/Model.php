<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database;

use Closure;
use Core\Database\Metrics\Metrics;
use Core\Exceptions\InvalidSQLQueryException;
use Core\Notification\Notifiable;
use PDOStatement;

/**
 * Manage database models.
 */
class Model
{
    use Notifiable;

    public function __construct(protected readonly string $table, protected array $attributes = [])
    {
    }

    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function findBy(string $column, $operator = null, $value = null): ?self
    {
        return (new Repository($this->table))->findWhere($column, $operator, $value);
    }

    public function getAll(array|string $columns): array
    {
        return (new Repository($this->table))->selectAll($columns);
    }

    public function first(array|string $columns): ?self
    {
        return (new Repository($this->table))->select($columns)->first();
    }

    public function last(array|string $columns): ?self
    {
        return (new Repository($this->table))->select($columns)->last();
    }

    public function take(int $count, ?Closure $subQuery = null): array
    {
        return (new Repository($this->table))
            ->select('*')
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->take($count);
    }

    public function oldest(array|string $columns, string $column = 'created_at', ?Closure $subQuery = null): array
    {
        return (new Repository($this->table))
            ->select($columns)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->oldest($column)
            ->getAll();
    }

    public function newest(array|string $columns, string $column = 'created_at', ?Closure $subQuery = null): array
    {
        return (new Repository($this->table))
            ->select($columns)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->newest($column)
            ->getAll();
    }

    public function select(array|string $columns): Repository
    {
        return (new Repository($this->table))->select($columns);
    }

    public function count(string $column = 'id', ?Closure $subQuery = null): string|array|int
    {
        $data = (new Repository($this->table))
            ->count($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->get('value');
    }

    public function sum(string $column, ?Closure $subQuery = null): string|array|int
    {
        $data = (new Repository($this->table))
            ->sum($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->get('value');
    }

    public function average(string $column, ?Closure $subQuery = null): string|int|array
    {
        $data = (new Repository($this->table))
            ->average($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->get('value');
    }

    public function max(string $column, ?Closure $subQuery = null): string|int|array
    {
        $data = (new Repository($this->table))
            ->max($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->get('value');
    }

    public function min(string $column, ?Closure $subQuery = null): string|int|array
    {
        $data = (new Repository($this->table))
            ->min($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->get('value');
    }

    public function metrics(): Metrics
    {
        return (new Repository($this->table))->metrics();
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function create(array $data): self|false
    {
        $id = (new Repository($this->table))->insertGetId($data);

        return is_null($id) ? false : $this->findBy('id', $id);
    }

    public function truncate(): false|PDOStatement
    {
        return (new Repository($this->table))->delete()->execute();
    }

    public function getId(): int
    {
        return (int) $this->get('id');
    }

    /**
     * Get relationship of the model.
     * @throws InvalidSQLQueryException
     */
    public function has(string $table, ?string $column = null): Repository
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return (new Repository($this->table))->select('*')->where($column, $this->getId());
    }

    /**
     * Get relationship belongs to the model.
     * @throws InvalidSQLQueryException
     */
    public function belongsTo(string $table, ?string $column = null): Repository
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return (new Repository($this->table))->select('*')->where('id', $this->attributes[$column]);
    }

    public function set(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }

    public function get(string|array $attributes = null): int|string|array|null
    {
        if (is_null($attributes)) {
            return $this->attributes;
        }

        if (is_string($attributes)) {
            return $this->attributes[$attributes] ?? null;
        }

        return array_intersect_key($this->attributes, array_flip($attributes));
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function update(array $data): bool
    {
        return (new Repository($this->table))->updateIfExists($this->getId(), $data);
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function delete(): bool
    {
        return (new Repository($this->table))->deleteIfExists($this->getId());
    }

    /**
     * @throws InvalidSQLQueryException
     */
    public function save(): self|false
    {
        if (empty($this->getId())) {
            return $this->create($this->attributes);
        }

        if ($this->update($this->attributes)) {
            return $this->findBy('id', $this->getId());
        }

        return false;
    }

    public function increment(string $column, int $value = 1): void
    {
        $this->attributes[$column] += $value;
    }

    public function decrement(string $column, int $value = 1): void
    {
        $this->attributes[$column] -= $value;
    }

    protected function getColumnFromTable(string $table): string
    {
        if ($table[-3] === 'ies') {
            $table = rtrim($table, 'ies');
            $table .= 'y';
        }

        if ($table[-1] === 's') {
            $table = rtrim($table, 's');
        }

        return $table . '_id';
    }
}

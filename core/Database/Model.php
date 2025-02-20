<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use Closure;
use Core\Database\Metrics\Metrics;
use Core\Notifications\Notifiable;
use PDOStatement;

/**
 * Manage database models.
 */
class Model
{
    use Notifiable;

    protected Repository $repository;

    public function __construct(protected readonly string $table, protected array $attributes = [])
    {
        $this->repository = new Repository($table);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function findBy(string $column, $operator = null, $value = null): self|false
    {
        return $this->repository->findWhere($column, $operator, $value);
    }

    public function getAll(): array|false
    {
        return $this->repository->selectAll('*');
    }

    public function first(): self|false
    {
        return $this->select('*')->first();
    }

    public function last(): self|false
    {
        return $this->select('*')->last();
    }

    public function take(int $count, ?Closure $subQuery = null): array|false
    {
        return $this->select('*')
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->take($count);
    }

    public function oldest(string $column = 'created_at', ?Closure $subQuery = null): array|false
    {
        return $this->select('*')
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->oldest($column)
            ->getAll();
    }

    public function newest(string $column = 'created_at', ?Closure $subQuery = null): array|false
    {
        return $this->select('*')
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->newest($column)
            ->getAll();
    }

    public function select(array|string $columns): Repository
    {
        return $this->repository->select($columns);
    }

    public function where(string $column, $operator = null, $value = null): Repository
    {
        return $this->select('*')->where($column, $operator, $value);
    }

    public function count(string $column = 'id', ?Closure $subQuery = null): string|array|false|int
    {
        $data = $this->repository
            ->count($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? false : $data->get('value');
    }

    public function sum(string $column, ?Closure $subQuery = null): string|array|false|int
    {
        $data = $this->repository
            ->sum($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? false : $data->get('value');
    }

    public function average(string $column, ?Closure $subQuery = null): string|int|false|array
    {
        $data = $this->repository
            ->average($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? false : $data->get('value');
    }

    public function max(string $column, ?Closure $subQuery = null): string|int|false|array
    {
        $data = $this->repository
            ->max($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? false : $data->get('value');
    }

    public function min(string $column, ?Closure $subQuery = null): string|int|false|array
    {
        $data = $this->repository
            ->min($column)
            ->subQueryWhen(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? false : $data->get('value');
    }

    public function metrics(): Metrics
    {
        return $this->repository->metrics();
    }

    public function create(array $data): self|false
    {
        $id = $this->repository->insertGetId($data);

        return is_null($id) ? false : $this->findBy('id', $id);
    }

    public function truncate(): false|PDOStatement
    {
        return $this->repository->delete()->execute();
    }

    public function getId(): int
    {
        return (int) $this->get('id');
    }

    /**
     * Get relationship of the model.
     */
    public function has(string $table, ?string $column = null): Repository
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return $this->select('*')->where($column, $this->getId());
    }

    /**
     * Get relationship belongs to the model.
     */
    public function belongsTo(string $table, ?string $column = null): Repository
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return $this->select('*')->where('id', $this->attributes[$column]);
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
            return $this->attributes[$attributes];
        }

        return array_intersect_key($this->attributes, array_flip($attributes));
    }

    public function update(array $data): bool
    {
        return $this->repository->updateIfExists($this->getId(), $data);
    }

    public function delete(): bool
    {
        return $this->repository->deleteIfExists($this->getId());
    }

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

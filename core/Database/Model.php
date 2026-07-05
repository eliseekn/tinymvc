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
use Core\Database\Metrics\Metrics;
use Core\Notification\Notifiable;
use Core\Observer\Observer;
use Core\Policy\Policy;
use Core\Policy\PolicyInterface;

/**
 * Manage database models.
 */
class Model
{
    use Notifiable;

    public readonly Repository $repository;

    public function __construct(public readonly string $table, public array $attributes = [], public array $updatedAttributes = [])
    {
        $this->repository = new Repository($this->table);
    }

    public function wasUpdated(string|array $attributes): bool
    {
        if (is_string($attributes)) {
            return isset($this->updatedAttributes[$attributes]) && $this->updatedAttributes[$attributes] !== $this->attributes[$attributes];
        }

        $result = false;

        foreach ($attributes as $attribute) {
            if (isset($this->updatedAttributes[$attribute])) {
                $result = isset($this->updatedAttributes[$attribute]) && $this->updatedAttributes[$attribute] !== $this->attributes[$attribute];
            }
        }

        return $result;
    }

    public function findBy(string $column, $operator = null, $value = null): ?self
    {
        return $this->repository->findWhere($column, $operator, $value);
    }

    public function getAll(array|string $columns): array
    {
        return $this->repository->selectAll($columns);
    }

    public function first(array|string $columns): ?self
    {
        return $this->repository->select($columns)->first();
    }

    public function last(array|string $columns): ?self
    {
        return $this->repository->select($columns)->last();
    }

    public function take(array|string $columns, int $count, ?Closure $subQuery = null): array
    {
        return $this->repository
            ->select($columns)
            ->when(! is_null($subQuery), $subQuery)
            ->take($count);
    }

    public function oldest(array|string $columns, string $column = 'created_at', ?Closure $subQuery = null): array
    {
        return $this->repository
            ->select($columns)
            ->when(! is_null($subQuery), $subQuery)
            ->oldest($column)
            ->getAll();
    }

    public function newest(array|string $columns, string $column = 'created_at', ?Closure $subQuery = null): array
    {
        return $this->repository
            ->select($columns)
            ->when(! is_null($subQuery), $subQuery)
            ->newest($column)
            ->getAll();
    }

    public function select(array|string $columns): Repository
    {
        return $this->repository->select($columns);
    }

    public function count(string $column = 'id', ?Closure $subQuery = null): string|array|int
    {
        $data = $this->repository
            ->count($column)
            ->when(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->getAttributes('value');
    }

    public function sum(string $column, ?Closure $subQuery = null): string|array|int
    {
        $data = $this->repository
            ->sum($column)
            ->when(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->getAttributes('value');
    }

    public function average(string $column, ?Closure $subQuery = null): string|int|array
    {
        $data = $this->repository
            ->average($column)
            ->when(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->getAttributes('value');
    }

    public function max(string $column, ?Closure $subQuery = null): string|int|array
    {
        $data = $this->repository
            ->max($column)
            ->when(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->getAttributes('value');
    }

    public function min(string $column, ?Closure $subQuery = null): string|int|array
    {
        $data = $this->repository
            ->min($column)
            ->when(! is_null($subQuery), $subQuery)
            ->get();

        return ! $data ? 0 : $data->getAttributes('value');
    }

    public function metrics(): Metrics
    {
        return $this->repository->metrics();
    }

    public function create(array $data, bool $withoutEvent = false): self|false
    {
        $id = $this->repository->insertGetId($data);

        if (is_null($id)) {
            return false;
        }

        $model = $this->findBy('id', $id);

        if (is_null($model)) {
            return false;
        }

        if (! $withoutEvent) {
            Observer::created($model);
        }

        return $model;
    }

    public function getId(): int
    {
        return (int) $this->getAttributes('id');
    }

    /**
     * Get relationship of the model.
     */
    public function hasOne(string $table, ?string $column = null): ?Model
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return $this->repository
            ->select('*')
            ->where($column, $this->getId())
            ->get();
    }

    /**
     * Get relationship of the model.
     */
    public function hasMany(string $table, ?string $column = null): array
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return $this->repository
            ->select('*')
            ->where($column, $this->getId())
            ->getAll();
    }

    /**
     * Get relationship belongs to the model.
     */
    public function belongsTo(string $table, ?string $column = null): ?Model
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return (new Repository($table))
            ->select('*')
            ->where('id', $this->attributes[$column])
            ->get();
    }

    /**
     * Get relationship belongs to many the model.
     */
    public function belongsToMany(string $table, ?string $column = null): array
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return (new Repository($table))
            ->select('*')
            ->where('id', $this->attributes[$column])
            ->getAll();
    }

    public function setAttributes(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if (isset($this->attributes[$key])) {
                $this->updatedAttributes[$key] = $this->attributes[$key];
            }

            $this->attributes[$key] = $value;
        }

        return $this;
    }

    public function getAttributes(string|array|null $attributes = null): int|string|array|null
    {
        if (is_null($attributes)) {
            return $this->attributes;
        }

        if (is_string($attributes)) {
            return $this->attributes[$attributes] ?? null;
        }

        return array_intersect_key($this->attributes, array_flip($attributes));
    }

    public function update(array $data, bool $withoutEvent = false): bool
    {
        $result = $this->repository->updateIfExists($this->getId(), $data);

        if (! $result) {
            return false;
        }

        if (! $withoutEvent) {
            Observer::updated(new self($this->table, $this->attributes, empty($this->updatedAttributes) ? $data : $this->updatedAttributes));
        }

        return true;
    }

    public function delete(bool $withoutEvent = false): bool
    {
        $result = $this->repository->deleteIfExists($this->getId());

        if (! $result) {
            return false;
        }

        if (! $withoutEvent) {
            Observer::deleted($this);
        }

        return true;
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

        return $table.'_id';
    }

    public function isAuthorized(PolicyInterface $policy): Policy
    {
        return Policy::authorize($policy, $this);
    }
}

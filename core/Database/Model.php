<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use PDOStatement;

/**
 * Manage database models
 */
class Model
{
    protected Repository $repository;

    public function __construct(protected readonly string $table, protected array $attributes = [])
    {
        $this->repository = new Repository($table);
    }

    public function findBy(string $column, $operator = null, $value = null): self|false
    {
        return $this->repository->findWhere($column, $operator, $value);
    }

    public function find(int $id): self|false
    {
        return $this->findBy('id', $id);
    }

    public function getAll(): array|false
    {
        return $this->repository->selectAll('*');
    }

    public function first(): Model|false
    {
        return $this->select('*')->first();
    }

    public function last(): Model|false
    {
        return $this->select('*')->last();
    }

    public function take(int $count, $subquery = null): array|false
    {
        return $this->select('*')->subQuery($subquery)->take($count);
    }

    public function oldest(string $column = 'created_at', $subquery = null): array|false
    {
        return $this->select('*')->subQuery($subquery)->oldest($column)->getAll();
    }

    public function newest(string $column = 'created_at', $subquery = null): array|false
    {
        return $this->select('*')->subQuery($subquery)->newest($column)->getAll();
    }

    public function latest(string $column = 'id', $subquery = null): array|false
    {
        return $this->select('*')->subQuery($subquery)->latest($column)->getAll();
    }

    public function select(array|string $columns): Repository
    {
        return $this->repository->select($columns);
    }

    public function where(string $column, $operator = null, $value = null): Repository
    {
        return $this->select('*')->where($column, $operator, $value);
    }

    public function count(string $column = 'id', $subquery = null): mixed
    {
        $data = $this->repository->count($column)->subQuery($subquery)->get();
        return !$data ? false : $data->attribute('value');
    }

    public function sum(string $column, $subquery = null): mixed
    {
        $data = $this->repository->sum($column)->subQuery($subquery)->get();
        return !$data ? false : $data->attribute('value');
    }

    public function max(string $column, $subquery = null): mixed
    {
        $data = $this->repository->max($column)->subQuery($subquery)->get();
        return !$data ? false : $data->attribute('value');
    }

    public function min(string $column, $subquery = null): mixed
    {
        $data = $this->repository->min($column)->subQuery($subquery)->get();
        return !$data ? false : $data->attribute('value');
    }

    public function metrics(string $column, string $type, string $period, int $interval = 0, ?array $query = null): mixed
    {
        return $this->repository->metrics($column, $type, $period, $interval, $query);
    }

    public function trends(string $column, string $type, string $period, int $interval = 0, ?array $query = null): array
    {
        return $this->repository->trends($column, $type, $period, $interval, $query);
    }

    public function create(array $data): self|false
    {
        $id = $this->repository->insertGetId($data);
        return is_null($id) ? false : $this->find($id);
    }

    public function truncate(): false|PDOStatement
    {
        return $this->repository->delete()->execute();
    }

    public function getId(): int
    {
        return (int) $this->attributes['id'];
    }
    
    /**
     * Get relationship of the model
     */
    public function has(string $table, ?string $column = null): Repository
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($this->table);
        }

        return (new Repository($table))->select('*')->where($column, $this->attributes['id']);
    }

    /**
     * Get relationship belongs to the model
     */
    public function belongsTo(string $table, ?string $column = null): Repository
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return (new Repository($table))->select('*')->where('id', $this->attributes[$column]);
    }

    public function attribute(string $key): mixed
    {
        return $this->attributes[$key];
    }

    public function fill(array $data): self
    {
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }

        return $this;
    }
    
    public function update(array $data): bool
    {
        return $this->repository->updateIfExists($this->attributes['id'], $data);
    }

    public function delete(): bool
    {
        return $this->repository->deleteIfExists($this->attributes['id']);
    }

    public function save(): Model|false
    {
        if (empty($this->attributes['id'])) {
            return $this->create($this->attributes);
        }

        if ($this->update($this->attributes)) {
            return $this->find($this->attributes['id']);
        }

        return false;
    }

    public function increment(string $column, $value = null): void
    {
        if (is_null($value)) {
            $this->attributes[$column]++;
            return;
        }
            
        $this->attributes[$column] = $this->attributes[$column] + $value;
    }

    public function decrement(string $column, $value = null): void
    {
        if (is_null($value)) {
            $this->attributes[$column]--;
            return;
        }

        $this->attributes[$column] = $this->attributes[$column] - $value;
    }

    public function toArray(array $attributes = null): array
    {
        $data = $this->attributes;

        if (empty($this->attributes['id'])) {
            unset($data['id']);
        }

        if (is_null($attributes)) {
            return $this->attributes;
        }

        $attributes = parse_array($attributes);
        $result = [];

        foreach ($attributes as $attribute) {
            if (isset($attribute)) {
                $result = array_merge($result, [
                    $attribute => $this->attributes[$attribute]
                ]);
            }
        }

        return $result;
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


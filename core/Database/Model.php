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
    protected static $table = '';
    protected array $attributes = [];

    public function __construct($data = [])
    {
        $this->attributes = $data;
    }

    public static function findBy(string $column, $operator = null, $value = null): self|false
    {
        return (new Repository(static::$table))->findWhere($column, $operator, $value);
    }

    public static function find(int $id): self|false
    {
        return self::findBy('id', $id);
    }

    public static function all(): array|false
    {
        return (new Repository(static::$table))->selectAll('*');
    }

    public static function first(): Model|false
    {
        return self::select('*')->first();
    }

    public static function last(): Model|false
    {
        return self::select('*')->last();
    }

    public static function take(int $count, $subquery = null): array|false
    {
        return self::select('*')->subQuery($subquery)->take($count);
    }

    public static function oldest(string $column = 'created_at', $subquery = null): array|false
    {
        return self::select('*')->subQuery($subquery)->oldest($column)->getAll();
    }

    public static function newest(string $column = 'created_at', $subquery = null): array|false
    {
        return self::select('*')->subQuery($subquery)->newest($column)->getAll();
    }

    public static function latest(string $column = 'id', $subquery = null): array|false
    {
        return self::select('*')->subQuery($subquery)->latest($column)->getAll();
    }

    public static function select(array|string $columns): Repository
    {
        return (new Repository(static::$table))->select($columns);
    }

    public static function where(string $column, $operator = null, $value = null): Repository
    {
        return self::select('*')->where($column, $operator, $value);
    }

    public static function count(string $column = 'id', $subquery = null): mixed
    {
        $data = (new Repository(static::$table))->count($column)->subQuery($subquery)->get();

        if (!$data) {
            return false;
        }

        return $data->attribute('value');
    }

    public static function sum(string $column, $subquery = null): mixed
    {
        $data = (new Repository(static::$table))->sum($column)->subQuery($subquery)->get();

        if (!$data) {
            return false;
        }

        return $data->attribute('value');
    }

    public static function max(string $column, $subquery = null): mixed
    {
        $data = (new Repository(static::$table))->max($column)->subQuery($subquery)->get();

        if (!$data) {
            return false;
        }

        return $data->attribute('value');
    }

    public static function min(string $column, $subquery = null): mixed
    {
        $data = (new Repository(static::$table))->min($column)->subQuery($subquery)->get();

        if (!$data) {
            return false;
        }

        return $data->attribute('value');
    }

    public static function metrics(string $column, string $type, string $period, int $interval = 0, ?array $query = null): mixed
    {
        return (new Repository(static::$table))->metrics($column, $type, $period, $interval, $query);
    }

    public static function trends(string $column, string $type, string $period, int $interval = 0, ?array $query = null): array
    {
        return (new Repository(static::$table))->trends($column, $type, $period, $interval, $query);
    }

    public static function create(array $data): self|false
    {
        $id = (new Repository(static::$table))->insertGetId($data);

        if (is_null($id)) {
            return false;
        }

        return self::find($id);
    }

    /**
     * Delete all rows
     */
    public static function truncate(): false|PDOStatement
    {
        return (new Repository(static::$table))->delete()->execute();
    }

    public function getId(): int
    {
        return (int) $this->attributes['id'];
    }
    
    /**
     * Get relationship of the model 
     *
     * @param  string $table
     * @param  mixed $column
     * @return \Core\Database\Repository
     */
    public function has(string $table, ?string $column = null): Repository
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable(static::$table);
        }

        return (new Repository($table))->select('*')->where($column, $this->attributes['id']);
    }
    
    /**
     * Get relationship belongs to the model
     *
     * @param  string $table
     * @param  mixed $column
     * @return \Core\Database\Repository
     */
    public function belongsTo(string $table, ?string $column = null): Repository
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return (new Repository($table))->select('*')->where('id', $this->attributes[$column]);
    }

    public function attribute(string $key, $value = null): mixed
    {
        if (!is_null($value)) {
            $this->attributes[$key] = $value;
        }

        return $this->attributes[$key];
    }
    
    /**
     * Fill model attributes with custom data
     *
     * @param  array $data
     * @return void
     */
    public function fill(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }
    
    public function update(array $data): false|self
    {
        return !(new Repository(static::$table))->updateIfExists($this->attributes['id'], $data) ? false : $this;
    }

    public function delete(): bool
    {
        return (new Repository(static::$table))->deleteIfExists($this->attributes['id']);
    }

    public function save(): self|false
    {
        return empty($this->attributes['id'])
            ? self::create($this->attributes)
            : $this->update($this->attributes);
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


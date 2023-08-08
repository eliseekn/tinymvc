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
    public $id;
    public static $table = '';
    public static $attributes = null;

    public function __construct(string $table, $data = null)
    {
        static::$table = $table;
        static::$attributes = $data;
    }

    public static function findBy(string $column, $operator = null, $value = null): self|false
    {
        $data = (new Repository(static::$table))->findWhere($column, $operator, $value);

        if (!$data) {
            return false;
        }

        return new self(static::$table, $data);
    }

    public static function find(int $id): self|false
    {
        return self::findBy('id', $id);
    }

    public static function all(): array|false
    {
        return (new Repository(static::$table))->selectAll('*');
    }

    public static function first(): mixed
    {
        return self::select('*')->first();
    }

    public static function last(): mixed
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
        return (new Repository(static::$table))->count($column)->subQuery($subquery)->get()->value;
    }

    public static function sum(string $column, $subquery = null): mixed
    {
        return (new Repository(static::$table))->sum($column)->subQuery($subquery)->get()->value;
    }

    public static function max(string $column, $subquery = null): mixed
    {
        return (new Repository(static::$table))->max($column)->subQuery($subquery)->get()->value;
    }

    public static function min(string $column, $subquery = null): mixed
    {
        return (new Repository(static::$table))->min($column)->subQuery($subquery)->get()->value;
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

        return (new Repository($table))->select('*')->where($column, $this->id);
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

        return (new Repository($table))->select('*')->where('id', static::$attributes[$column]);
    }

    public function attribute(string $key, $value = null): mixed
    {
        if (!is_null($value)) {
            static::$attributes[$key] = $value;
        }

        return static::$attributes[$key];
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
            static::$attributes[$key] = $value;
        }
    }
    
    public function update(array $data): false|self
    {
        return !(new Repository(static::$table))->updateIfExists($this->id, $data) ? false : $this;
    }

    public function delete(): bool
    {
        return (new Repository(static::$table))->deleteIfExists($this->id);
    }

    public function save(): self|false
    {
        return is_null($this->id)
            ? self::create((array) $this)
            : $this->update((array) $this);
    }

    public function increment(string $column, $value = null): void
    {
        if (is_null($value)) {
            static::$attributes[$column]++;
            return;
        }
            
        static::$attributes[$column] = static::$attributes[$column] + $value;
    }

    public function decrement(string $column, $value = null): void
    {
        if (is_null($value)) {
            static::$attributes[$column]--;
            return;
        }

        static::$attributes[$column] = static::$attributes[$column] - $value;
    }

    public function toArray(array|string $attributes = null): array
    {
        $data = static::$attributes;

        if (is_null($this->id)) {
            unset($data['id']);
        }

        if (!is_null($attributes)) {
            $attributes = parse_array($attributes);
            $d = [];

            foreach ($attributes as $attribute) {
                if (isset($attribute)) {
                    $d = array_merge($d, [
                        $attribute => static::$attributes[$attribute]
                    ]);
                }
            }

            $data = $d;
        }

        return $data;
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


<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

/**
 * Manage database models
 */
class Model
{
    public $id;
    public static $table = '';

    public function __construct(string $table, $data = null)
    {
        static::$table = $table;

        if (!is_null($data)) {
            foreach ($data as $key => $column) {
                $this->{$key} = $column;
            }
        }
    }

    public static function findBy(string $column, $operator = null, $value = null)
    {
        $data = (new Repository(static::$table))->findWhere($column, $operator, $value);

        if (!$data) {
            return false;
        }

        return new self(static::$table, $data);
    }

    public static function find(int $id)
    {
        return self::findBy('id', $id);
    }

    public static function all()
    {
        return (new Repository(static::$table))->selectAll('*');
    }

    public static function first()
    {
        return self::select('*')->first();
    }

    public static function last()
    {
        return self::select('*')->last();
    }

    public static function take(int $count, $callback = null)
    {
        return self::select('*')->subQuery($callback)->take($count);
    }

    public static function oldest(string $column = 'created_at', $callback = null)
    {
        return self::select('*')->subQuery($callback)->oldest($column)->getAll();
    }

    public static function newest(string $column = 'created_at', $callback = null)
    {
        return self::select('*')->subQuery($callback)->newest($column)->getAll();
    }

    public static function latest(string $column = 'id', $callback = null)
    {
        return self::select('*')->subQuery($callback)->latest($column)->getAll();
    }

    public static function select(string ...$columns)
    {
        return (new Repository(static::$table))->select(...$columns);
    }

    public static function where(string $column, $operator = null, $value = null)
    {
        return self::select('*')->where($column, $operator, $value);
    }

    public static function count(string $column = 'id', $callback = null)
    {
        return (new Repository(static::$table))->count($column)->subQuery($callback)->get()->value;
    }

    public static function sum(string $column, $callback = null)
    {
        return (new Repository(static::$table))->sum($column)->subQuery($callback)->get()->value;
    }

    public static function max(string $column, $callback = null)
    {
        return (new Repository(static::$table))->max($column)->subQuery($callback)->get()->value;
    }

    public static function min(string $column, $callback = null)
    {
        return (new Repository(static::$table))->min($column)->subQuery($callback)->get()->value;
    }

    public static function metrics(string $column, string $type, string $period, int $interval = 0, ?array $query = null)
    {
        return (new Repository(static::$table))->metrics($column, $type, $period, $interval, $query);
    }

    public function children(string $table, ?string $column = null)
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable(static::$table);
        }

        return (new Repository($table))->select('*')->where($column, $this->id);
    }

    public function parent(string $table, ?string $column = null)
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return (new Repository($table))->select('*')->where('id', $this->{$column});
    }

    public static function create(array $data)
    {
        $id = (new Repository(static::$table))->insertGetId($data);

        if (is_null($id)) {
            return false;
        }

        return self::find($id);
    }
    
    public function update(array $data)
    {
        if (!(new Repository(static::$table))->updateIfExists($this->id, $data)) {
            return false;
        }

        return $this;
    }

    public function delete()
    {
        return (new Repository(static::$table))->deleteIfExists($this->id);
    }

    public function save()
    {
        return is_null($this->id)
            ? self::create((array) $this)
            : $this->update((array) $this);
    }

    public function increment(string $column, $value = null)
    {
        if (is_null($value)) {
            $this->{$column}++;
        } else {
            $this->{$column} = $this->{$column} + $value;
        }

        return $this->save();
    }

    public function decrement(string $column, $value = null)
    {
        if (is_null($value)) {
            $this->{$column}--;
        } else {
            $this->{$column} = $this->{$column} - $value;
        }

        return $this->save();
    }

    protected function getColumnFromTable(string $table)
    {
        if ($table[-3] === 'ies') {
            $table = rtrim($table, 'ies');
            $table .= 'y';
        }
    
        if ($table[-1] === 's') {
            $table = rtrim($table, 's');
        }

        return $table .= '_id';
    }
}


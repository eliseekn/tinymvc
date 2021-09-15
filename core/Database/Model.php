<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use Core\Support\Storage;

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
            foreach ($data as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Generate model factory
     * 
     * @return \Core\Database\Factory
     */
    public static function factory(int $count = 1)
    {
        foreach (Storage::path(config('storage.factories'))->getFiles() as $file) {
            $factory = '\App\Database\Factories\\' . get_file_name($file);

            return new $factory($count);
        }
    }

    public static function findBy(string $column, $operator = null, $value = null)
    {
        $data = (new Repository(static::$table))->findWhere($column, $operator, $value);

        if (!$data) return false;

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

    public static function trends(string $column, string $type, string $period, int $interval = 0, ?array $query = null)
    {
        return (new Repository(static::$table))->trends($column, $type, $period, $interval, $query);
    }

    public static function create(array $data)
    {
        $id = (new Repository(static::$table))->insertGetId($data);

        if (is_null($id)) return false;

        return self::find($id);
    }

    /**
     * Delete all rows
     */
    public static function truncate()
    {
        (new Repository(static::$table))->delete()->execute();
    }
    
    /**
     * Get relationship of the model 
     *
     * @param  string $table
     * @param  string|null $column
     * @return \Core\Database\Repository
     */
    public function has(string $table, ?string $column = null)
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
     * @param  string|null $column
     * @return \Core\Database\Repository
     */
    public function belongsTo(string $table, ?string $column = null)
    {
        if (is_null($column)) {
            $column = $this->getColumnFromTable($table);
        }

        return (new Repository($table))->select('*')->where('id', $this->{$column});
    }

    public function set(string $key, $value)
    {
        $this->{$key} = $value;
    }

    public function get(string $key)
    {
        return $this->{$key};
    }
    
    /**
     * Fill model attributes with custom data
     *
     * @param  array $data
     * @return void
     */
    public function fill(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }
    
    public function update(array $data)
    {
        return !(new Repository(static::$table))->updateIfExists($this->id, $data) ? false : $this;
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
            return;
        }
            
        $this->{$column} = $this->{$column} + $value;
    }

    public function decrement(string $column, $value = null)
    {
        if (is_null($value)) {
            $this->{$column}--;
        }

        $this->{$column} = $this->{$column} - $value;
    }

    public function toArray(string ...$attributes)
    {
        $data = (array) $this;

        if (is_null($this->id)) unset($data['id']);

        if (!empty($attributes)) {
            $d = [];

            foreach ($attributes as $attribute) {
                if ($this->has($attribute)) {
                    $d = array_merge($d, [
                        $attribute => $this->{$attribute}
                    ]);
                }
            }

            $data = $d;
        }

        return $data;
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


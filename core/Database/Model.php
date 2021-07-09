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

    public static function id(int $id)
    {
        return self::findBy('id', $id);
    }

    public static function all()
    {
        return (new Repository(static::$table))->selectAll();
    }

    public static function select(string ...$columns)
    {
        return (new Repository(static::$table))->select(...$columns);
    }

    public static function where(string $column, $operator = null, $value = null)
    {
        return (new Repository(static::$table))->select('*')->where($column, $operator, $value);
    }

    public static function create(array $data)
    {
        $id = (new Repository(static::$table))->insertGetId($data);

        if (is_null($id)) {
            return false;
        }

        return self::id($id);
    }
    
    public function update(array $data)
    {
        return (new Repository(static::$table))->updateWhere(['id', $this->id], $data);
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
}

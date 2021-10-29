<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use Faker\Factory as FakerFactory;

/**
 * Manage models factories
 */
class Factory
{
    protected static $model;
    protected $class;

    /** @var \Faker\Generator $faker */
    public $faker;

    public function __construct(int $count = 1)
    {
        $this->faker = FakerFactory::create(config('app.lang'));

        if ($count === 1) {
            $this->class = new static::$model();
        } else {
            for ($i = 1; $i <= $count; $i++) {
                $this->class[] = new static::$model();
            }
        }
    }

    public function data()
    {
        return [];
    }

    /**
     * @return array|\Core\Database\Model
     */
    public function make(array $data = [])
    {
        if (!is_array($this->class)) {
            $this->class->fill(array_merge($this->data(), $data));
        } else {
            $this->class = array_map(function ($model) use ($data) {
                $model->fill(array_merge($this->data(), $data));
                return $model;
            }, $this->class);
        }

        return $this->class;
    }

    public function create(array $data = [])
    {
        $class = $this->make($data);

        if (!is_array($class)) {
            return $class->save();
        } else {
            $class = array_map(function ($c) {
                $c->save();
                return $c;
            }, $class);
        }

        return $class;
    }
}

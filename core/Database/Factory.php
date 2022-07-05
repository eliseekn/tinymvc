<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Database;

use Faker\Factory as FakerFactory;
use Faker\Generator;

/**
 * Manage models factories
 */
class Factory
{
    protected static string $model;
    protected array|Model $class;
    public Generator $faker;

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

    public static function fromModel(string $model, int $count = 1, ?string $namespace = null)
    {
        $model = explode('\\', $model);
        $model = end($model);

        $factory = !$namespace
            ? '\App\Database\Factories\\' . $model . 'Factory'
            : '\App\Database\Factories\\' . $namespace . '\\' . $model . 'Factory';

        return new $factory($count);
    }

    public function data()
    {
        return [];
    }

    public function make(array $data = []): array|Model
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

    public function create(array $data = []): array|Model
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

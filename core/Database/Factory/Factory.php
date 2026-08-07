<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Factory;

use Core\Database\Attributes\UseFactory;
use Core\Database\Attributes\UseModel;
use Core\Database\Model;
use Core\Exceptions\CoreException;
use ReflectionClass;

/**
 * Manage models factories.
 */
class Factory
{
    /** @var Model[] */
    protected array $class;

    public function __construct(int $count = 1)
    {
        $model = (new ReflectionClass(static::class))->getAttributes(UseModel::class);

        if (empty($model)) {
            throw new CoreException(sprintf('No model defined for the "%s" factory.', static::class));
        }

        $modelClass = $model[0]->newInstance()->name;

        for ($i = 1; $i <= $count; $i++) {
            $this->class[] = new $modelClass;
        }
    }

    /**
     * Resolve the factory registered for a model.
     */
    public static function for(string $model, int $count = 1): static
    {
        $factory = (new ReflectionClass($model))->getAttributes(UseFactory::class);

        if (empty($factory)) {
            throw new CoreException(sprintf('No factory defined for the "%s" model.', $model));
        }

        $factoryClass = $factory[0]->newInstance()->name;

        return new $factoryClass($count);
    }

    public function data(): array
    {
        return [];
    }

    public function make(array $data = []): Model|array|null
    {
        if (count($this->class) === 1) {
            $this->class[0]->setAttributes(array_merge($this->data(), $data));

            return $this->class[0];
        }

        return array_map(function ($model) use ($data) {
            $model->setAttributes(array_merge($this->data(), $data));

            return $model;
        }, $this->class);
    }

    public function create(array $data = [], bool $withoutEvent = false): Model|array|null
    {
        $class = $this->make($data);

        if (! is_array($class)) {
            return $class->create($class->getAttributes(), $withoutEvent);
        }

        return array_map(fn ($c) => $c->create($c->getAttributes(), $withoutEvent), $class);
    }
}

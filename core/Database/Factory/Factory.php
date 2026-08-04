<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Factory;

use Core\Database\Model;
use Core\Exceptions\CoreException;
use ReflectionClass;
use Spatie\StructureDiscoverer\Discover;

/**
 * Manage models factories.
 */
class Factory
{
    /**
     * Model class the factory produces. Every subclass must set this.
     *
     * @var class-string<Model>
     */
    public string $model;

    /** @var Model[] */
    protected array $class;

    public function __construct(int $count = 1)
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->class[] = new $this->model;
        }
    }

    /**
     * Resolve the factory registered for a model.
     */
    public static function for(string $model, int $count = 1): static
    {
        $factories = Discover::in(config('storage.factories'))->classes()->get();
        $factories = array_values(array_filter(
            $factories,
            fn ($factory) => is_a($factory, static::class, true)
                && (new ReflectionClass($factory))->getDefaultProperties()['model'] === $model
        ));

        if (empty($factories)) {
            throw new CoreException(sprintf('No factory found for the "%s" model.', $model));
        }

        return new $factories[0]($count);
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

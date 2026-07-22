<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database;

use BackedEnum;
use Carbon\Carbon;
use DateTimeInterface;
use ReflectionClass;
use ReflectionEnum;
use ReflectionNamedType;
use ReflectionProperty;

/**
 * Manage database entities.
 */
abstract class Entity
{
    protected ?int $id = null;

    protected ?Carbon $createdAt = null;

    protected ?Carbon $updatedAt = null;

    /**
     * Get the database table associated with the entity.
     */
    abstract public static function table(): string;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    /**
     * Create a new entity from a database row.
     */
    public static function fromArray(array $data): static
    {
        // @phpstan-ignore-next-line
        return (new static)->hydrate($data);
    }

    /**
     * Create a new entity from a model.
     */
    public static function fromModel(Model $model): static
    {
        return static::fromArray($model->getAttributes());
    }

    /**
     * Fill entity properties from a database row. Columns without
     * a matching property are kept in the extra attributes.
     */
    public function hydrate(array $row): static
    {
        $reflection = new ReflectionClass($this);

        foreach ($row as $column => $value) {
            $property = self::camelCase($column);
            $reflectionProperty = $reflection->getProperty($property);

            if ($reflectionProperty->isStatic()) {
                continue;
            }

            $reflectionProperty->setValue($this, self::castToProperty($reflectionProperty, $value));
        }

        return $this;
    }

    /**
     * Convert entity properties to an array of database columns.
     */
    public function toArray(): array
    {
        $attributes = [];

        foreach ((new ReflectionClass($this))->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->isStatic() || ! $reflectionProperty->isInitialized($this)) {
                continue;
            }

            $attributes[self::snakeCase($reflectionProperty->getName())] = self::castToColumn($reflectionProperty->getValue($this));
        }

        return $attributes;
    }

    /**
     * Convert entity to a model.
     */
    public function toModel(array $data = []): Model
    {
        $data = empty($data) ? $this->toArray() : $data;
        $modelClass = static::model();

        return new $modelClass(static::table(), $data);
    }

    /**
     * Model class associated with the entity. Override to bind the entity
     * to a specific Model subclass (e.g. for model-specific behavior like
     * notifications); defaults to the generic Model.
     *
     * @return class-string<Model>
     */
    protected static function model(): string
    {
        return Model::class;
    }

    protected static function castToProperty(ReflectionProperty $reflectionProperty, mixed $value): mixed
    {
        $type = $reflectionProperty->getType();

        if (is_null($value) || ! $type instanceof ReflectionNamedType) {
            return $value;
        }

        $name = $type->getName();

        return match (true) {
            $name === 'int' => (int) $value,
            $name === 'float' => (float) $value,
            $name === 'bool' => (bool) $value,
            $name === 'string' => (string) $value,
            is_subclass_of($name, BackedEnum::class) => self::castToEnum($name, $value),
            is_a($name, DateTimeInterface::class, true) => carbon(is_string($value) ? $value : $value->format('Y-m-d H:i:s')),
            default => $value,
        };
    }

    protected static function castToEnum(string $enum, mixed $value): BackedEnum
    {
        $backingType = (new ReflectionEnum($enum))->getBackingType();

        return $backingType?->getName() === 'int'
            ? $enum::from((int) $value)
            : $enum::from((string) $value);
    }

    protected static function castToColumn(mixed $value): mixed
    {
        return match (true) {
            $value instanceof BackedEnum => $value->value,
            $value instanceof DateTimeInterface => $value->format('Y-m-d H:i:s'),
            is_bool($value) => (int) $value,
            default => $value,
        };
    }

    protected static function camelCase(string $column): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $column))));
    }

    protected static function snakeCase(string $property): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
    }
}

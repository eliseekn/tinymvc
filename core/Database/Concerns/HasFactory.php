<?php

namespace Core\Database\Concerns;

use Core\Database\Factory;

trait HasFactory
{
    public static function factory(int $count = 1, ?string $namespace = null)
    {
        return Factory::fromModel(get_called_class(), $count, $namespace);
    }
}
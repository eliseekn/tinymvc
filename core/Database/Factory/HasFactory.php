<?php

namespace Core\Database\Factory;

use Spatie\StructureDiscoverer\Discover;

trait HasFactory
{
    public static function factory(int $count = 1)
    {
        $factories = Discover::in(config('storage.factories'))->classes()->get();
        $factories = array_values(
            array_filter($factories, fn ($factory) => (new $factory())->getModelName() === get_called_class())
        );

        return new $factories[0]($count);
    }
}

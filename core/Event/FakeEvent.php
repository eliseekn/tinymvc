<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Event;

use Core\Support\Storage;

class FakeEvent
{
    protected static function storage(): Storage
    {
        return storage(config('storage.tmp'));
    }

    public static function load(string|array $name): void
    {
        static::storage()->writeFile('events.json', json_encode(parse_array($name)));
        static::storage()->writeFile('dispatched_events.json', '');
    }

    public static function dispatch(string|array $name): void
    {
        $names = parse_array($name);
        $dispatched = static::dispatchedEvents();

        foreach ($names as $name) {
            if (in_array($name, static::events())) {
                $dispatched[] = $name;
            }
        }

        static::storage()->writeFile('dispatched_events.json', json_encode($dispatched), true);
    }

    public static function events(): array
    {
        if (! static::storage()->isFile('events.json')) {
            return [];
        }

        $data = static::storage()->readFile('events.json');

        if (empty($data)) {
            return [];
        }

        return json_decode($data, true);
    }

    public static function dispatchedEvents(): array
    {
        if (! static::storage()->isFile('dispatched_events.json')) {
            return [];
        }

        $data = static::storage()->readFile('dispatched_events.json');

        if (empty($data)) {
            return [];
        }

        return json_decode($data, true);
    }

    public static function clear(): void
    {
        static::storage()->deleteFile('events.json');
        static::storage()->deleteFile('dispatched_events.json');
    }
}

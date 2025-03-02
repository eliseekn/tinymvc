<?php

declare(strict_types=1);

namespace Core\Event;

class Event
{
    protected static array $events = [];

    public static function load(): void
    {
        $events = config('events');

        if (! empty($events)) {
            foreach ($events as $name => $listener) {
                self::$events[$name] = $listener;
            }
        }
    }

    public static function dispatch(string $name, object $event): void
    {
        foreach (self::$events[$name] as $listener) {
            call_user_func_array([new $listener(), '__invoke'], [$event]);
        }
    }
}

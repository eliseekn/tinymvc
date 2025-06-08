<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

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
            call_user_func_array([new $listener, '__invoke'], [$event]);
        }
    }
}

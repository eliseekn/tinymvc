<?php

namespace Core\Events;

use Core\Exceptions\InvalidEventNameException;
use Core\Support\DependencyInjection;

class Event
{
    protected static array $events = [];

    public static function listen(string $name, $callback): void
    {
        if (!self::isValidName($name)) {
            throw new InvalidEventNameException();
        }

        self::$events[$name][] = $callback;
    }

    public static function dispatch(string $name, $params = []): void
    {
        foreach (self::$events[$name] as $event => $callback) {
            (new DependencyInjection())->resolve($callback, '__invoke', $params);
        }
    }

    public static function loadListeners(): void
    {
        if (!empty(config('events.listeners'))) {
            $listeners = config('events.listeners');

            foreach ($listeners as $event => $callback) {
                self::listen($event, $callback);
            }
        }
    }

    /**
     * @link https://github.com/eliseekn/roolith-event/blob/master/src/Event.php
     */
    protected static function isValidName(string $name): bool
    {
        return (bool) preg_match('/^[a-zA-Z0-9.*_]+$/', $name);
    }
}
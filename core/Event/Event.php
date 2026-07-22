<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Event;

use Core\Event\Events\ModelNotFound\ModelNotFoundEvent;
use Core\Event\Events\ModelNotFound\SendNotFoundResponse;
use PHPUnit\Framework\Assert;

class Event
{
    protected static array $events = [
        ModelNotFoundEvent::class => [
            SendNotFoundResponse::class,
        ],
    ];

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
        if (in_array($name, FakeEvent::events())) {
            FakeEvent::dispatch($name);

            return;
        }

        foreach (self::$events[$name] as $listener) {
            call_user_func_array([new $listener, '__invoke'], [$event]);
        }
    }

    public static function fake(string|array $name): void
    {
        FakeEvent::load($name);
    }

    public static function assertDispatched(string|array $name): void
    {
        $names = parse_array($name);

        foreach ($names as $_name) {
            Assert::assertTrue(
                in_array($name, FakeEvent::dispatchedEvents()),
            );
        }

        FakeEvent::clear();
    }

    public static function assertNotDispatched(string|array $name): void
    {
        $names = parse_array($name);

        foreach ($names as $_name) {
            Assert::assertFalse(
                in_array($name, FakeEvent::dispatchedEvents()),
            );
        }

        FakeEvent::clear();
    }
}

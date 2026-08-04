<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit\Fake;

use Core\Event\Event;
use Core\Event\FakeEvent;
use PHPUnit\Framework\TestCase;

class FakeEventTest extends TestCase
{
    protected function tearDown(): void
    {
        FakeEvent::clear();

        parent::tearDown();
    }

    public function test_can_fake_and_assert_dispatched_event(): void
    {
        Event::fake('App\\Events\\UserCreated\\UserCreatedEvent');

        Event::dispatch('App\\Events\\UserCreated\\UserCreatedEvent', new \stdClass);

        $this->assertSame(
            ['App\\Events\\UserCreated\\UserCreatedEvent'],
            FakeEvent::dispatchedEvents(),
        );

        Event::assertDispatched('App\\Events\\UserCreated\\UserCreatedEvent');
    }

    public function test_can_fake_multiple_events(): void
    {
        Event::fake([
            'App\\Events\\UserCreated\\UserCreatedEvent',
            'App\\Events\\UserRegistered\\UserRegisteredEvent',
        ]);

        Event::dispatch('App\\Events\\UserCreated\\UserCreatedEvent', new \stdClass);
        Event::dispatch('App\\Events\\UserRegistered\\UserRegisteredEvent', new \stdClass);

        Event::assertDispatched([
            'App\\Events\\UserCreated\\UserCreatedEvent',
            'App\\Events\\UserRegistered\\UserRegisteredEvent',
        ]);
    }

    public function test_can_merge_faked_events(): void
    {
        Event::fake('App\\Events\\UserCreated\\UserCreatedEvent');
        Event::fake('App\\Events\\UserRegistered\\UserRegisteredEvent');

        $this->assertSame(
            [
                'App\\Events\\UserCreated\\UserCreatedEvent',
                'App\\Events\\UserRegistered\\UserRegisteredEvent',
            ],
            FakeEvent::events(),
        );
    }

    public function test_does_not_record_unfaked_event(): void
    {
        Event::fake('App\\Events\\UserCreated\\UserCreatedEvent');

        FakeEvent::dispatch('App\\Events\\UserRegistered\\UserRegisteredEvent');

        $this->assertSame([], FakeEvent::dispatchedEvents());
    }

    public function test_assert_not_dispatched(): void
    {
        Event::fake('App\\Events\\UserCreated\\UserCreatedEvent');

        Event::assertNotDispatched('App\\Events\\UserRegistered\\UserRegisteredEvent');
    }
}

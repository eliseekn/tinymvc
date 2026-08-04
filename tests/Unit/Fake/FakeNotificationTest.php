<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit\Fake;

use Core\Notification\FakeNotification;
use Core\Notification\Notification;
use Core\Notification\NotificationInterface;
use PHPUnit\Framework\TestCase;

class FakeNotificationTest extends TestCase
{
    protected function tearDown(): void
    {
        FakeNotification::clear();

        parent::tearDown();
    }

    public function test_can_fake_and_assert_sent_notification(): void
    {
        Notification::fake(ExampleMail::class, 'user@example.com');

        Notification::send(new ExampleMail)->to('user@example.com');

        $this->assertSame(
            [ExampleMail::class => 'user@example.com'],
            FakeNotification::sentNotifications(),
        );

        Notification::assertSent(ExampleMail::class, 'user@example.com');
    }

    public function test_can_merge_faked_notifications(): void
    {
        Notification::fake(ExampleMail::class, 'user@example.com');
        Notification::fake(AnotherMail::class, 'admin@example.com');

        $this->assertSame(
            [
                ExampleMail::class => ['user@example.com'],
                AnotherMail::class => ['admin@example.com'],
            ],
            FakeNotification::notifications(),
        );
    }

    public function test_does_not_record_unfaked_notification(): void
    {
        Notification::fake(ExampleMail::class, 'user@example.com');

        Notification::send(new AnotherMail)->to('admin@example.com');

        $this->assertSame([], FakeNotification::sentNotifications());
    }

    public function test_assert_not_sent(): void
    {
        Notification::fake(ExampleMail::class, 'user@example.com');

        Notification::assertNotSent(ExampleMail::class, 'other@example.com');
    }
}

class ExampleMail implements NotificationInterface
{
    public function to(string|array $recipient, string|array $name = []): self
    {
        return $this;
    }

    public function send(?string $message = null) {}
}

class AnotherMail implements NotificationInterface
{
    public function to(string|array $recipient, string|array $name = []): self
    {
        return $this;
    }

    public function send(?string $message = null) {}
}

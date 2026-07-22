<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification;

use PHPUnit\Framework\Assert;

class Notification
{
    public function __construct(public NotificationInterface $notifiable)
    {
    }

    public static function send(NotificationInterface $notifiable): self
    {
        // @phpstan-ignore-next-line
        return new static($notifiable);
    }

    public function to(string|array $recipient): void
    {
        if (array_key_exists($this->notifiable::class, FakeNotification::notifications())) {
            FakeNotification::send($this->notifiable::class, $recipient);

            return;
        }

        $this->notifiable->to($recipient)->send();
    }

    public static function fake(string $name, string|array $recipient): void
    {
        FakeNotification::load($name, $recipient);
    }

    public static function assertSent(string $name, string|array $recipient): void
    {
        $notifications = FakeNotification::sentNotifications();

        Assert::assertTrue(
            array_key_exists($name, $notifications) && $notifications[$name] === $recipient,
        );

        FakeNotification::clear();
    }

    public static function assertNotSent(string $name, string|array $recipient): void
    {
        $notifications = FakeNotification::sentNotifications();

        Assert::assertFalse(
            array_key_exists($name, $notifications) && $notifications[$name] === $recipient,
        );

        FakeNotification::clear();
    }
}

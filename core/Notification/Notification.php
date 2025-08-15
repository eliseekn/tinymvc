<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification;

use Core\Exceptions\NotificationNotSentException;

class Notification
{
    public function __construct(public NotificationInterface $notifiable)
    {
    }

    public static function send(NotificationInterface $mail): self
    {
        // @phpstan-ignore-next-line
        return new static($mail);
    }

    /**
     * @throws NotificationNotSentException
     */
    public function to(string $recipient): void
    {
        $this->notifiable->to($recipient);

        if (! $this->notifiable->send()) {
            throw new NotificationNotSentException;
        }
    }
}

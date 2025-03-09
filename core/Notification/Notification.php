<?php

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
            throw new NotificationNotSentException();
        }
    }
}

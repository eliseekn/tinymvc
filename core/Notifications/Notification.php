<?php

namespace Core\Notifications;

use Core\Exceptions\NotificationNotSentException;

class Notification
{
    public function __construct(public NotificationInterface $notifiable) {}

    public static function send(NotificationInterface $mail): self
    {
        return new static($mail);
    }

    public function to(string $recipient): void
    {
        $this->notifiable->to($recipient);

        if (! $this->notifiable->send()) {
            throw new NotificationNotSentException();
        }
    }
}

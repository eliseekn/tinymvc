<?php

namespace Core\Notifications\Mail;

use Core\Notifications\Mail\Mailer\MailerInterface;
use Core\Notifications\NotificationInterface;

class Mail implements NotificationInterface
{
    public function __construct(public MailerInterface $mailer) {}

    public function to(string $address, string $name = ''): self
    {
        $this->mailer->to($address, $name);

        return $this;
    }

    public function send(): bool
    {
        return $this->mailer->send();
    }
}
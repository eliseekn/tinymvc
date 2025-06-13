<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification\Mail;

use Core\Notification\Mail\Mailer\MailerInterface;
use Core\Notification\NotificationInterface;

class Mail implements NotificationInterface
{
    public function __construct(public MailerInterface $mailer)
    {
    }

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

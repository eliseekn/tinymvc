<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification\Sms;

use Core\Notification\NotificationInterface;

class Sms implements NotificationInterface
{
    protected Twilio $twilio;

    public function __construct()
    {
        $this->twilio = new Twilio;
    }

    public function to(string|array $recipient, string|array $name = []): self
    {
        $this->twilio->to($recipient);

        return $this;
    }

    public function send(?string $message = null): bool
    {
        return $this->twilio->send($message);
    }
}

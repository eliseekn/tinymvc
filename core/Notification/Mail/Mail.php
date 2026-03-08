<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification\Mail;

use Core\Notification\NotificationInterface;
use Core\Services\PHPMailer;

class Mail implements NotificationInterface
{
    protected PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer;
    }

    public function to(string|array $recipient, string|array $name = []): self
    {
        $this->mailer->to($recipient, $name);

        return $this;
    }

    public function send(?string $message = null): bool
    {
        return $this->mailer->send();
    }
}

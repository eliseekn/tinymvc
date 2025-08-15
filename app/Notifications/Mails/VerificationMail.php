<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Notifications\Mails;

use Core\Notification\Mail\Mail;

class VerificationMail extends Mail
{
    public function __construct(public string $email, public string $token)
    {
        parent::__construct();
    }

    public function send(?string $message = null): bool
    {
        return $this->mailer
            ->from(config('notifications.mail.sender.email'), config('notifications.mail.sender.name'))
            ->replyTo(config('notifications.mail.sender.email'), config('notifications.mail.sender.name'))
            ->subject(__('email.email_verification_subject'))
            ->html('emails.verification', [
                'email' => $this->email,
                'token' => $this->token,
            ])
            ->send();
    }
}

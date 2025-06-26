<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Mails;

use Core\Notification\Mail\Mail;
use Core\Notification\Mail\Mailer\Mailer;

class PasswordResetMail extends Mail
{
    public function __construct(public string $email, public string $token)
    {
        parent::__construct(new Mailer);
    }

    public function send(): bool
    {
        return $this->mailer
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->replyTo(config('mailer.sender.email'), config('mailer.sender.name'))
            ->subject(__('email.password_reset_subject'))
            ->html('emails.password_reset', [
                'email' => $this->email,
                'token' => $this->token,
            ])
            ->send();
    }
}

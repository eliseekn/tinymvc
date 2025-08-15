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

class AccountCreatedMail extends Mail
{
    public function __construct(public string $password, public string $url)
    {
        parent::__construct();
    }

    public function send(?string $message = null): bool
    {
        return $this->mailer
            ->from(config('notifications.mail.sender.email'), config('notifications.mail.sender.name'))
            ->replyTo(config('notifications.mail.sender.email'), config('notifications.mail.sender.name'))
            ->subject('Account created')
            ->html('emails.account_created', [
                'password' => $this->password,
                'url' => $this->url,
            ])
            ->send();
    }
}

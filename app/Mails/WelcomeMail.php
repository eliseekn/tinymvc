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

class WelcomeMail extends Mail
{
    public function __construct(public string $name)
    {
        parent::__construct();
    }

    public function send(): bool
    {
        return $this->mailer
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->replyTo(config('mailer.sender.email'), config('mailer.sender.name'))
            ->subject(__('email.welcome_subject'))
            ->html('emails.welcome', ['name' => $this->name])
            ->send();
    }
}

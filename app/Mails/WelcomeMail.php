<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Mails;

use Core\Notifications\Mail\Mail;
use Core\Notifications\Mail\Mailer\Mailer;

class WelcomeMail extends Mail
{
    public function __construct(public string $name)
    {
        parent::__construct(new Mailer());
    }

    public function send(): bool
    {
        return $this
            ->mailer
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->replyTo(config('mailer.sender.email'), config('mailer.sender.name'))
            ->subject(__('welcome_mail_subject'))
            ->html('emails.welcome', ['name' => $this->name])
            ->send();
    }
}

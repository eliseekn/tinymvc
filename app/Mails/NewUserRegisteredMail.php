<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Mails;

use Core\Database\Model;
use Core\Notification\Mail\Mail;
use Core\Notification\Mail\Mailer\Mailer;

class NewUserRegisteredMail extends Mail
{
    public function __construct(public Model $user, public string $url)
    {
        parent::__construct(new Mailer());
    }

    public function send(): bool
    {
        return $this
            ->mailer
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->replyTo(config('mailer.sender.email'), config('mailer.sender.name'))
            ->subject('New user registered')
            ->html('emails.new_user_registered', [
                'user' => $this->user,
                'url' => $this->url,
            ])
            ->send();
    }
}

<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Mails;

use Core\Support\Mail\Mailer;

class VerificationMail
{
    public static function send(string $email, string $token): bool
    {
        return (new Mailer())
            ->to($email)
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->reply(config('mailer.sender.email'), config('mailer.sender.name'))
			->subject('Email verification')
            ->body(view('emails.verification', compact('email', 'token')))
            ->send();
    }
}

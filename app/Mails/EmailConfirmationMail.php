<?php

namespace App\Mails;

use Framework\Routing\View;
use Framework\Support\Email;

class EmailConfirmationMail
{
    /**
     * send email notification
     *
     * @param  string $email
     * @param  string $token
     * @return bool
     */
    public static function send(string $email, string $token): bool
    {
        return Email::to($email)
            ->from(config('mailer.from'), config('mailer.name'))
            ->reply(config('mailer.from'), config('mailer.name'))
			->subject('Email confirmation')
            ->html(View::getContent('emails.emailconfirmation', compact('email', 'token')))
			->send();
    }
}
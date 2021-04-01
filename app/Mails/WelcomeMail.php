<?php

namespace App\Mails;

use Framework\Routing\View;
use Framework\Support\Email;

class WelcomeMail
{
    /**
     * send welcome email notification
     *
     * @param  string $email
     * @param  string $username
     * @return bool
     */
    public static function send(string $email, string $username): bool
    {
        return Email::to($email)
            ->from(config('mailer.from'), config('mailer.name'))
            ->reply(config('mailer.from'), config('mailer.name'))
			->subject('Welcome')
            ->html(View::getContent('emails.welcome', compact('username')))
			->send();
    }
}
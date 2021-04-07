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
            ->from(config('mailer.sender_mail'), config('mailer.sender_name'))
            ->reply(config('mailer.sender_mail'), config('mailer.sender_name'))
			->subject('Welcome')
            ->html(View::getContent('emails.welcome', compact('username')))
			->send();
    }
}
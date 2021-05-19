<?php

namespace App\Mails;

use Core\Routing\View;
use Core\System\Mailer;

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
        return Mailer::to($email)
            ->from(config('mailer.sender_email'), config('mailer.sender_name'))
            ->reply(config('mailer.sender_email'), config('mailer.sender_name'))
			->subject('Welcome')
            ->html(View::getContent('emails.welcome', compact('username')))
			->send();
    }
}

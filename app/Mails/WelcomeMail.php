<?php

namespace App\Mails;

use Core\Routing\View;
use Core\Support\Mailer;

class WelcomeMail
{
    /**
     * Send welcome email notification
     */
    public static function send(string $email, string $username)
    {
        return Mailer::to($email)
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->reply(config('mailer.sender.email'), config('mailer.sender.name'))
			->subject('Welcome')
            ->body(View::getContent('emails.welcome', compact('username')))
			->send();
    }
}

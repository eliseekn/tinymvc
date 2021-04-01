<?php

namespace App\Mails;

use Framework\Routing\View;
use Framework\Support\Email;

class AuthLinkMail
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
			->subject('Authentification link')
            ->html(View::getContent('emails.authlink', compact('email', 'token')))
			->send();
    }
}
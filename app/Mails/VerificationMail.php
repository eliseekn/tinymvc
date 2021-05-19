<?php

namespace App\Mails;

use Core\Routing\View;
use Core\System\Mailer;

class VerificationMail
{
    /**
     * send email verification notification
     *
     * @param  string $email
     * @param  string $token
     * @return bool
     */
    public static function send(string $email, string $token): bool
    {
        return Mailer::to($email)
            ->from(config('mailer.sender_email'), config('mailer.sender_name'))
            ->reply(config('mailer.sender_email'), config('mailer.sender_name'))
			->subject('Email verification')
            ->html(View::getContent('emails.verification', compact('email', 'token')))
			->send();
    }
}
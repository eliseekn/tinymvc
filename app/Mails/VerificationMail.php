<?php

namespace App\Mails;

use Core\Routing\View;
use Core\Support\Mailer;

class VerificationMail
{
    /**
     * Send email verification notification
     */
    public static function send(string $email, string $token)
    {
        return Mailer::to($email)
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->reply(config('mailer.sender.email'), config('mailer.sender.name'))
			->subject('Email verification')
            ->html(View::getContent('emails.verification', compact('email', 'token')))
			->send();
    }
}
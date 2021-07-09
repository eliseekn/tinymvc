<?php

namespace App\Mails;

use Core\Routing\View;
use Core\Support\Mailer;

class TokenMail
{
    /**
     * Send password reset token link notification
     */
    public static function send(string $email, string $token)
    {
        return Mailer::to($email)
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->reply(config('mailer.sender.email'), config('mailer.sender.name'))
			->subject('Password reset')
            ->html(View::getContent('emails.token', compact('email', 'token')))
			->send();
    }
}
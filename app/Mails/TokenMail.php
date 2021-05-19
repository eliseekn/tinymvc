<?php

namespace App\Mails;

use Core\Routing\View;
use Core\System\Mailer;

class TokenMail
{
    /**
     * send password reset token link notification
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
			->subject('Password reset')
            ->html(View::getContent('emails.token', compact('email', 'token')))
			->send();
    }
}
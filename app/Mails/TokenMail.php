<?php

namespace App\Mails;

use Framework\Routing\View;
use Framework\System\Email;

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
        return Email::to($email)
            ->from(config('mailer.sender_mail'), config('mailer.sender_name'))
            ->reply(config('mailer.sender_mail'), config('mailer.sender_name'))
			->subject('Password reset notification')
            ->html(View::getContent('emails.token', compact('email', 'token')))
			->send();
    }
}
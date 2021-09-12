<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Mails;

use Core\Routing\View;
use Core\Support\Mailer\MailerInterface;

class TokenMail
{
    /**
     * Send password reset token link notification
     */
    public static function send(MailerInterface $mailer, string $email, string $token)
    {
        return $mailer->to($email, '')
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->reply(config('mailer.sender.email'), config('mailer.sender.name'))
			->subject('Password reset')
            ->body(View::getContent('emails.token', compact('email', 'token')))
			->send();
    }
}
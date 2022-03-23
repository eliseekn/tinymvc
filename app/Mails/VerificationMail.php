<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Mails;

use Core\Routing\View;
use Core\Support\Mail\Mailer;

/**
 * Send email verification notification
 */
class VerificationMail extends Mailer
{
    private string $email;
    private string $token;

    public function __construct(string $email, string $token)
    {
        $this->email = $email;
        $this->token = $token;
    }

    public function send(): bool
    {
        return $this->to($this->email)
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->reply(config('mailer.sender.email'), config('mailer.sender.name'))
			->subject('Email verification')
            ->body(View::getContent('emails.verification', ['email' => $this->email, 'token' => $this->token]))
			->send();
    }
}

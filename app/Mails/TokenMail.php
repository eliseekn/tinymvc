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
 * Send password reset token link notification
 */
class TokenMail extends Mailer
{
    public function __construct(string $email, string $token) {
        parent::__construct();
        
        $this->to($email)
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->reply(config('mailer.sender.email'), config('mailer.sender.name'))
            ->subject('Password reset')
            ->body(View::getContent('emails.verification', compact('email', 'token')));
    }
}

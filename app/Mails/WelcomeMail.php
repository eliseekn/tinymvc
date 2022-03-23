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
 * Send welcome email notification
 */
class WelcomeMail extends Mailer
{
    private string $email;
    private string $username;

    public function __construct(string $email, string $username)
    {
        $this->email = $email;
        $this->username = $username;
    }

    public function send(): bool
    {
        return $this->to($this->email)
            ->from(config('mailer.sender.email'), config('mailer.sender.name'))
            ->reply(config('mailer.sender.email'), config('mailer.sender.name'))
			->subject('Welcome')
            ->body(View::getContent('emails.welcome', ['username' => $this->username]))
			->send();
    }
}

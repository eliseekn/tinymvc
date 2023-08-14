<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Events\UserRegistered;

use App\Mails\WelcomeMail;
use Core\Support\Mail\Mail;

class UserRegisteredEventListener
{
    public function __invoke($user): void
    {
        Mail::send(new WelcomeMail($user->attribute('email'), $user->attribute('name')));
    }
}

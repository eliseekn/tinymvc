<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support\Mail;

class Mail
{
    public static function send(MailInterface $mailInterface): bool
    {
        return $mailInterface->send();
    }
}

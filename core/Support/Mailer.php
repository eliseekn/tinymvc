<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Core\Support\BaseMailer;

/**
 * Send emails
 */
class Mailer
{
    public static function to(string $address, string $name = '')
    {
        return (new BaseMailer())->to($address, $name);
    }
}

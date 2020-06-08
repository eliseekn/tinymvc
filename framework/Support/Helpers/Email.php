<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

/**
 * Email utils functions
 */

/**
 * send email using built-in PHP mail function
 *
 * @param  string $to email address of receiver
 * @param  string $subject email subject
 * @param  string $message email message content
 * @param  string $headers additionnal headers as enumerated array
 * @return bool returns true or false if email sent or not
 */
function send_email(string $to, string $subject, string $message, array $headers = []): bool
{
    return mail($to, $subject, $message, $headers);
}

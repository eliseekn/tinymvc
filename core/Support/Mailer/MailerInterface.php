<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support\Mailer;

/**
 * Send emails
 */
interface MailerInterface
{
    public function to(string $address, string $name);

    public function from(string $address, string $name);

    public function reply(string $address, string $name);
    
    public function cc(string $address, string $name);
    
    public function bcc(string $address, string $name);

    public function subject(string $subject);

    public function body(string $message, bool $html);

    public function attachment(string $attachment, string $filename);
    
    public function send();
}

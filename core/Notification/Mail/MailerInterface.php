<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Notification\Mail;

interface MailerInterface
{
    public function to(string $address, string $name);

    public function from(string $address, string $name);

    public function replyTo(string $address, string $name);

    public function cc(string $address, string $name);

    public function bcc(string $address, string $name);

    public function subject(string $subject);

    public function text(string $body);

    public function html(string $body, array $data = []);

    public function attachment(string $attachment, string $filename);

    public function send();
}
